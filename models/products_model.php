<?php

class products_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "products";
    private $_table = "products p LEFT JOIN products_types t ON p.pro_type_id=t.type_id";
    private $_field = "p.*, type_name";
    private $_cutNamefield = "pro_";
    #p LEFT JOIN products_categories pt ON p.pro_type_id=pt.type_id

    public function lists( $options=array() ){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'id',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'ASC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( isset($options['not']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
        	$where_str .= "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }

        if( isset($_REQUEST["type"]) ){
            $options["type"] = $_REQUEST["type"];
        }
        if( !empty($options['type']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}type_id=:type";
            $where_arr[':type'] = $options['type'];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = empty($options['unlimit'])? $this->limited( $options['limit'], $options['pager'] ): '';

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }

    public function insert( &$data ){
        $data["{$this->_cutNamefield}created"] = date("c");
        $data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->insert( $this->_objName, $data);
        $data["id"] = $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $data["{$this->_cutNamefield}updated"] = date("c");
      $this->db->update( $this->_objName, $data, "`{$this->_cutNamefield}id`={$id}" );
    }

    public function delete($id){
    	$this->db->delete( $this->_objName, "`{$this->_cutNamefield}id`={$id}" );
    }

    public function get($id, $options=array()){
      $select = $this->_field;

      $sth = $this->db->prepare("SELECT {$select} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
      $sth->execute( array(
        ':id' => $id
      ) );

      return $sth->rowCount()==1
      ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options )
      : array();
    }
    public function buildFrag($results, $options=array()) {
      $data = array();
      foreach ($results as $key => $value) {
        if( empty($value) ) continue;
        $data[] = $this->convert($value, $options);
      }
      return $data;

    }
    public function convert($data, $options=array()){
    	$data = $this->cut($this->_cutNamefield, $data);

        $data['permit']['del'] = true;
        /* $data['pallet_total'] = $this->db->select('pallets', 'pallet_pro_id=:id', array(':id'=>$id));
        if( !empty($data['pallet_total']) ){
            $data['permit']['del'] = false;
        } */
    	// $data["type"] = $this->getType( $data["type_id"] );

    	return $data;
    }

    #Grade
    private $grade_objName = "products_grade";
    private $grade_select = "grade_type_id AS type_id, grade_id AS id, grade_name AS name, type_name";
    private $grade_table = "products_grade g LEFT JOIN products_types t ON g.grade_type_id=t.type_id";
    public function grade( $options=array() ){

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST["sort"]) ? $_REQUEST["sort"]:"id",
            'dir' => isset($_REQUEST["dir"]) ? $_REQUEST["dir"]:"ASC",

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST["type"]) ){
            $options["type"] = $_REQUEST["type"];
        }
        if( !empty($options["type"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "grade_type_id=:type";
            $where_arr[":type"] = $options["type"];
        }

        $arr['total'] = $this->db->count($this->grade_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}" : "";

        $orderby = $this->orderby( 'grade_'.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->db->select("SELECT {$this->grade_select} FROM {$this->grade_table} {$where_str} {$orderby} {$limit}", $where_arr);
        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function getGrade( $id ){
        $select = $this->grade_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->grade_table} WHERE `grade_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertGrade( $data ){
        $this->db->insert($this->grade_objName, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function updateGrade($id, $data){
        $this->db->update($this->grade_objName, $data, "grade_id={$id}");
    }
    public function deleteGrade($id){
        $this->db->delete($this->grade_objName, "grade_id={$id}");
    }
    public function is_grade($type, $name){
        return $this->db->count($this->grade_table, "grade_type_id=:type AND grade_name=:name", array(":type"=>$type, ":name"=>$name));
    }

    #Type
    private $type_select = "type_id AS id
                            , type_code AS code
                            , type_name AS name
                            , type_icon AS icon
                            , type_has_amount AS has_amount
                            , type_has_breed AS has_breed";
    private $type_table = "products_types";
    public function type( $options=array() ){

    	$w = "";
    	$w_arr = array();

    	return $this->db->select("SELECT {$this->type_select} FROM {$this->type_table} {$w} ORDER BY type_has_amount ASC", $w_arr);
    }
    public function getType( $id ){
    	$select = $this->type_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->type_table} WHERE `type_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertType( &$data ){
    	$this->db->insert( $this->type_table, $data );
    	$data["type_id"] = $this->db->lastInsertId();
    }
    public function updateType( $id, $data ){
    	$this->db->update( $this->type_table, $data, "`type_id`={$id}" );
    }
    public function delType( $id ){
    	$this->db->delete( $this->type_table, "`type_id`={$id}" );
    }
    public function is_typeName( $text ){
    	return $this->db->count( $this->type_table, "`type_name`=:name", array(":name"=>$text) );
    }
    public function is_typeCode( $text ){
        return $this->db->count( $this->type_table, "`type_code`=:code", array(":code"=>$text) );
    }

    #Size
    private $size_select = "size_id AS id, size_name AS name";
    private $size_table = "products_size";
    public function size( $options=array() ){

    	$w = "";
    	$w_arr = array();

    	return $this->db->select("SELECT {$this->size_select} FROM {$this->size_table} {$w}", $w_arr);
    }
    public function getSize( $id ){
    	$select = $this->size_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->size_table} WHERE `size_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertSize( &$data ){
    	$this->db->insert( $this->size_table, $data );
    	$data["size_id"] = $this->db->lastInsertId();
    }
    public function updateSize( $id, $data ){
    	$this->db->update( $this->size_table, $data, "`size_id`={$id}" );
    }
    public function delSize( $id ){
    	$this->db->delete( $this->size_table, "`size_id`={$id}" );
    }
    public function is_sizeName( $text ){
    	return $this->db->count( $this->size_table, "`size_name`=:name", array(":name"=>$text) );
    }

    #Brand
    private $brand_select = "brand_id AS id, brand_code AS code, brand_name AS name, brand_status AS status";
    private $brand_table = "products_brands";
    public function brand( $options=array() ){

        $w = "";
        $w_arr = array();

        if( !empty($options["status"]) ){
            $w .= !empty($w) ? " AND " : "";
            $w .= "brand_status=:status";
            $w_arr[":status"] = $options["status"];
        }


        $data = $this->db->select("SELECT {$this->brand_select} FROM {$this->brand_table} {$w}", $w_arr);

        foreach ($data as $key => $value) {
            $data[$key]["status_arr"] = $this->getBrandStatus($value["status"]);
        }

        return $data;
    }
    public function getBrand( $id ){
        $select = $this->brand_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->brand_table} WHERE `brand_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["status_arr"] = $this->getBrandStatus( $data["status"] );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertBrand( &$data ){
        $data["brand_created"] = date("c");
        $data["brand_updated"] = date("c");
        $data["brand_status"] = "enabled";

        $this->db->insert( $this->brand_table, $data );
        $data["brand_id"] = $this->db->lastInsertId();
    }
    public function updateBrand( $id, $data ){
        $data["brand_updated"] = date("c");

        $this->db->update( $this->brand_table, $data, "`brand_id`={$id}" );
    }
    public function delBrand( $id ){
        $this->db->delete( $this->brand_table, "`brand_id`={$id}" );
    }
    public function is_brandName( $text ){
        return $this->db->count( $this->brand_table, "`brand_name`=:name", array(":name"=>$text) );
    }
    public function is_brandCode( $text ){
        return $this->db->count( $this->brand_table, "`brand_code`=:code", array(":code"=>$text) );
    }
    public function brandStatus(){

        $a[] = array("id"=>"enabled", "name"=>"เปิดใช้งาน");
        $a[] = array("id"=>"disabled", "name"=>"ปิดใช้งาน");

        return $a;
    }
    public function getBrandStatus( $id ){
        $data = array();
        foreach ($this->brandStatus() as $key => $value) {
            if( $value["id"] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }

    #Can
    private $can_select = "can_id AS id, can_code AS code, can_name AS name";
    private $can_table = "products_cans";
    public function can( $options=array() ){

        $w = "";
        $w_arr = array();

        // if( !empty($options["status"]) ){
        //     $w .= !empty($w) ? " AND " : "";
        //     $w .= "brand_status=:status";
        //     $w_arr[":status"] = $options["status"];
        // }

        $data = $this->db->select("SELECT {$this->can_select} FROM {$this->can_table} {$w}", $w_arr);

        // foreach ($data as $key => $value) {
        //     $data[$key]["status_arr"] = $this->getBrandStatus($value["status"]);
        // }

        return $data;
    }
    public function getCan( $id ){

        $sth = $this->db->prepare("SELECT {$this->can_select} FROM {$this->can_table} WHERE `can_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        // $data["status_arr"] = $this->getBrandStatus( $data["status"] );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertCan( &$data ){
        /* $data["can_created"] = date("c");
        $data["can_updated"] = date("c");
        $data["can_status"] = "enabled"; */

        $this->db->insert( $this->can_table, $data );
        $data["id"] = $this->db->lastInsertId();
    }
    public function updateCan( $id, $data ){
        // $data["can_updated"] = date("c");

        $this->db->update( $this->can_table, $data, "`can_id`={$id}" );
    }
    public function delCan( $id ){
        $this->db->delete( $this->can_table, "`can_id`={$id}" );
    }
    public function is_canName( $text ){
        return $this->db->count( $this->can_table, "`can_name`=:name", array(":name"=>$text) );
    }
    public function is_canCode( $text ){
        return $this->db->count( $this->can_table, "`can_code`=:code", array(":code"=>$text) );
    }

    private $canType_select = "type_id AS id, type_name AS name";
    private $canType_table = "products_cans_type";
    public function canType( $options=array() ){

        $w = "";
        $w_arr = array();

        // if( !empty($options["status"]) ){
        //     $w .= !empty($w) ? " AND " : "";
        //     $w .= "brand_status=:status";
        //     $w_arr[":status"] = $options["status"];
        // }

        $data = $this->db->select("SELECT {$this->canType_select} FROM {$this->canType_table} {$w}", $w_arr);

        // foreach ($data as $key => $value) {
        //     $data[$key]["status_arr"] = $this->getBrandStatus($value["status"]);
        // }

        return $data;
    }
    public function getcanType( $id ){

        $sth = $this->db->prepare("SELECT {$this->canType_select} FROM {$this->canType_table} WHERE `type_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        // $data["status_arr"] = $this->getBrandStatus( $data["status"] );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertcanType( &$data ){
        /* $data["can_created"] = date("c");
        $data["can_updated"] = date("c");
        $data["can_status"] = "enabled"; */

        $this->db->insert( $this->canType_table, $data );
        $data["id"] = $this->db->lastInsertId();
    }
    public function updatecanType( $id, $data ){
        // $data["can_updated"] = date("c");

        $this->db->update( $this->canType_table, $data, "`type_id`={$id}" );
    }
    public function delcanType( $id ){
        $this->db->delete( $this->canType_table, "`type_id`={$id}" );
    }
    public function is_canTypeName( $text ){
        return $this->db->count( $this->canType_table, "`type_name`=:name", array(":name"=>$text) );
    }

    #Brix
    private $brix_select = "brix_id AS id, brix_name AS name";
    private $brix_table = "products_brix";
    public function brix(){
        return $this->db->select("SELECT {$this->brix_select} FROM {$this->brix_table}");
    }
    public function getBrix( $id ){
        $select = $this->brix_table;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->brix_select} WHERE `brix_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertBrix( $data ){
        $this->db->insert($this->brix_table, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function updateBrix($id, $data){
        $this->db->update($this->brix_table, $data, "brix_id={$id}");
    }
    public function deleteBrix($id){
        $this->db->delete($this->brix_table, "brix_id={$id}");
    }

    #Option Can
    public function canOptions(){
        $a[] = array('id'=>'neck', 'name'=>'NECK');
        $a[] = array('id'=>'non', 'name'=>'NON NECK');

        return $a;
    }
    public function getCanOptions($id){
        $data = array();

        foreach ($this->canOptions() as $key => $value) {
            if( $id == $value['id'] ){
                $data = $value;
                break;
            }
        }

        return $data;
    }
    public function canLid(){
        $a[] = array('id'=>'EOE', 'name'=>'EOE');
        $a[] = array('id'=>'NL', 'name'=>'NL');

        return $a;
    }
    public function canBrand(){
        $a[] = array('id'=>'SMI', 'name'=>'SMI');
        $a[] = array('id'=>'LC', 'name'=>'LC');

        return $a;
    }

    #BREED
    private $breed_objName = "products_breed";
    private $breed_select = "breed_type_id AS type_id, breed_id AS id, breed_code AS code, breed_name AS name, type_name";
    private $breed_table = "products_breed b LEFT JOIN products_types t ON b.breed_type_id=t.type_id";
    public function breed( $options=array() ){

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST["sort"]) ? $_REQUEST["sort"]:"id",
            'dir' => isset($_REQUEST["dir"]) ? $_REQUEST["dir"]:"ASC",

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST["type"]) ){
            $options['type'] = $_REQUEST["type"];
        }
        if( !empty($options["type"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "breed_type_id=:type";
            $where_arr[":type"] = $options["type"];
        }

        $arr['total'] = $this->db->count($this->breed_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}" : "";

        $orderby = $this->orderby( 'breed_'.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->db->select("SELECT {$this->breed_select} FROM {$this->breed_table} {$where_str} {$orderby} {$limit}", $where_arr);
        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function getBreed( $id ){
        $select = $this->breed_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->breed_table} WHERE `breed_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertBreed( $data ){
        $this->db->insert($this->breed_objName, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function updateBreed($id, $data){
        $this->db->update($this->breed_objName, $data, "breed_id={$id}");
    }
    public function deleteBreed($id){
        $this->db->delete($this->breed_objName, "breed_id={$id}");
    }
    public function is_breed($type, $name){
        return $this->db->count($this->breed_table, "breed_type_id=:type AND breed_name=:name", array(":type"=>$type, ":name"=>$name));
    }

    #OLD
    private $old_objName = "products_old";
    private $old_select = "old_type_id AS type_id, old_id AS id, old_code AS code, type_name";
    private $old_table = "products_old o LEFT JOIN products_types t ON o.old_type_id=t.type_id";
    public function old( $options=array() ){

        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST["sort"]) ? $_REQUEST["sort"]:"id",
            'dir' => isset($_REQUEST["dir"]) ? $_REQUEST["dir"]:"ASC",

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $where_str = '';
        $where_arr = array();

        if( isset($_REQUEST["type"]) ){
            $options["type"] = $_REQUEST["type"];
        }
        if( !empty($options["type"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "old_type_id=:type";
            $where_arr[":type"] = $options["type"];
        }

        $arr['total'] = $this->db->count($this->old_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}" : "";

        $orderby = $this->orderby( 'old_'.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->db->select("SELECT {$this->old_select} FROM {$this->old_table} {$where_str} {$orderby} {$limit}", $where_arr);
        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function getOld( $id ){
        $select = $this->old_select;

        $sth = $this->db->prepare("SELECT {$select} FROM {$this->old_table} WHERE `old_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertOld( &$data ){
        $this->db->insert($this->old_objName, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function updateOld($id, $data){
        $this->db->update($this->old_objName, $data, "old_id={$id}");
    }
    public function deleteOld($id){
        $this->db->delete($this->old_objName, "old_id={$id}");
    }
    public function is_old($type, $name){
        return $this->db->count($this->old_table, "old_type_id=:type AND old_code=:name", array(":type"=>$type,":name"=>$name));
    }

    #Weight
    public function weight($options=array()){
        return $this->db->select("SELECT weight_id AS id, weight_dw AS dw, weight_nw AS nw FROM products_weight ORDER BY weight_dw ASC");
    }
    public function getWeight($id, $options=array()){
        $sth = $this->db->prepare("SELECT weight_id AS id, weight_dw AS dw, weight_nw AS nw FROM products_weight WHERE `weight_id`=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        $data["permit"]["del"] = true;
        $data["pallet_total"] = $this->db->count('pallets', "pallet_weight_id=:id", array(":id"=>$id));
        if( !empty($data["pallet_total"]) ){
            $data["permit"]["del"] = false;
        }

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertWeight(&$data){
        $this->db->insert('products_weight', $data);
    }
    public function updateWeight($id, $data){
        $this->db->update('products_weight', $data, "weight_id={$id}");
    }
    public function deleteWeight($id){
        $this->db->delete('products_weight', "weight_id={$id}");
    }
    public function is_weight($dw, $nw){
        return $this->db->count('products_weight', "weight_dw={$dw} AND weight_nw={$nw}");
    }

    public function sizeWeight($id=null){
        $data = array();

        $_size = $this->db->select("SELECT s.size_id as id, s.size_name as name, p.weight_id
            FROM products_size s
                LEFT JOIN permit_type_size_weight p ON s.size_id=p.size_id
            WHERE p.type_id=:id", array(
            ':id' => $id
        ));

        foreach ($_size as $key => $value) {
            $data[$value['id']] = $value;
            $data[$value['id']]['weight'][] = $this->getWeight($value['weight_id']);
        }

        return $data;
    }
}
