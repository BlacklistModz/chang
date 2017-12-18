<?php
class pallets_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "pallets";
    private $_table = "pallets p
    				   LEFT JOIN products_types t ON p.pallet_type_id=t.type_id
    				   LEFT JOIN products pd ON p.pallet_pro_id=pd.pro_id
    				   LEFT JOIN products_grade pg ON p.pallet_grade_id=pg.grade_id
    				   LEFT JOIN products_size ps ON p.pallet_size_id=ps.size_id
    				   LEFT JOIN products_weight pw ON p.pallet_weight_id=pw.weight_id
                       LEFT JOIN products_breed pr ON p.pallet_breed_id=pr.breed_id
                       LEFT JOIN products_old po ON p.pallet_old_id=po.old_id
    				   LEFT JOIN products_cans pc ON p.pallet_can_id=pc.can_id
    				   LEFT JOIN products_brix pb ON p.pallet_brix_id=pb.brix_id
                       LEFT JOIN warehouse w ON p.pallet_ware_id=w.ware_id
                       LEFT JOIN warehouse_rows wr ON p.pallet_row_id=wr.row_id";
    private $_field = "p.*, type_name, pro_code, pro_amount, grade_name, size_name, weight_dw, weight_nw, breed_name, old_code, can_name, brix_name, w.ware_name, wr.row_name";
    private $_cutNamefield = "pallet_";

    public function lists( $options=array() ){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'unlimit' => isset($_REQUEST['unlimit'])? $_REQUEST['unlimit']:true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,
            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        if( !empty($options['type']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}type_id=:type";
            $where_arr[':type'] = $options['type'];
        }

        if( isset($_REQUEST["period_start"]) && isset($_REQUEST["period_end"]) ){
            $options["period_start"] = $_REQUEST["period_start"];
            $options["period_end"] = $_REQUEST["period_end"];
        }
        if( !empty($options['period_start']) && !empty($options['period_end']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "({$this->_cutNamefield}date BETWEEN :s AND :e)";
            $where_arr[":s"] = $options["period_start"];
            $where_arr[":e"] = $options["period_end"];
        }

        if( isset($_REQUEST["warehouse"]) ){
            $options["warehouse"] = $_REQUEST["warehouse"];
        }
        if( !empty($options["warehouse"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}ware_id=:warehouse";
            $where_arr[":warehouse"] = $options["warehouse"];
        }

        if( isset($_REQUEST["rows"]) ){
            $options["rows"] = $_REQUEST["rows"];
        }
        if( !empty($options["rows"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}row_id=:rows";
            $where_arr[":rows"] = $options["rows"];
        }

        if( isset($_REQUEST["size"]) ){
            $options["size"] = $_REQUEST["size"];
        }
        if( !empty($options["size"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}size_id=:size";
            $where_arr[":size"] = $options["size"];
        }

        if( isset($_REQUEST["weight"]) ){
            $options["weight"] = $_REQUEST["weight"];
        }
        if( !empty($options["weight"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}weight_id=:weight";
            $where_arr[":weight"] = $options["weight"];
        }

        if( isset($_REQUEST["grade"]) ){
            $options["grade"] = $_REQUEST["grade"];
        }
        if( !empty($options["grade"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}grade_id=:grade";
            $where_arr[":grade"] = $options["grade"];
        }

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "p.pallet_code LIKE :q{$key}
                        OR grade_name LIKE :q{$key}
                        OR size_name LIKE :q{$key}
                        OR weight_dw LIKE :q{$key}
                        OR brix_name LIKE :q{$key}
                        OR ware_name LIKE :q{$key}
                        OR row_name LIKE :q{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
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

    	$data["{$this->_cutNamefield}created"] = date('c');
    	$data["{$this->_cutNamefield}updated"] = date('c');

        if( empty($data['pallet_code']) ){
            $data["{$this->_cutNamefield}code"] = $this->updateAutoCode($data);
        }

    	$this->db->insert( $this->_objName, $data );
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
    	$data["{$this->_cutNamefield}updated"] = date('c');

        if( empty($data['pallet_code']) && !empty($data['pallet_type_id']) && !empty($data['pallet_date']) ){
            $data["{$this->_cutNamefield}code"] = $this->updateAutoCode($data);
        }

    	$this->db->update( $this->_objName, $data, "{$this->_cutNamefield}id={$id}" );
    }
    public function delete( $id ){
    	$this->db->delete( $this->_objName, "{$this->_cutNamefield}id={$id}" );
        $this->delItems($id);
    }
    public function get($id, $options=array()){

        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
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

        $data['name_str'] = $data['code'];
        if( !empty($data['delivery_code']) ){
            $data['name_str'] .= ' ('.$data['delivery_code'].')';
        }

    	$data['neck'] = $this->query('products')->getCanOptions( $data['neck'] );
        $data['status_arr'] = $this->getStatus($data['status']);

    	if( !empty($options['items']) ){
            $data['items'] = $this->listsItems($data['id']);
        }

        if( !empty($options['hold']) ){
            $data['holds'] = $this->query('hold')->lists( array("pallet"=>$data['id']) );
        }

        $data['total_hole'] = $this->summaryHold( $data['id'] );

        $data['permit']['del'] = true;

    	return $data;
    }
    public function is_code($code, $type, $date){
        return $this->db->count($this->_objName, "{$this->_cutNamefield}code=:code AND {$this->_cutNamefield}type_id=:type AND {$this->_cutNamefield}date=:date", array(':code'=>$code, ':type'=>$type, ':date'=>$date));
    }
    public function is_delivery($code){
        return $this->db->count($this->_objName, "{$this->_cutNamefield}delivery_code=:code", array(":code"=>$code));
    }

    #Lists
    private $i_objType = 'pallets_items';
    private $i_select = "pi.*";
    private $i_table = "pallets_items pi
    					LEFT JOIN products p ON pi.item_pro_id=p.pro_id
    					LEFT JOIN products_size ps ON pi.item_size_id=ps.size_id
    					LEFT JOIN products_types t ON pi.item_type_id=t.type_id";
    public function listsItems($id, $options=array()){

        $where_str = 'pi.item_pallet_id=:id';
        $where_arr[':id'] = $id;

        if( !empty($options['status']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "pi.item_status=:status";
            $where_arr[':status'] = $options['status'];
        }

        $limit = '';
        if( !empty($options['limit']) ) {
            $limit = "LIMIT {$options['limit']}";
        }

        $where_str = !empty($where_str) ? "WHERE {$where_str}" : "";
    	return $this->buildFragItem( $this->db->select("SELECT {$this->i_select} FROM {$this->i_table} {$where_str} ORDER BY item_id ASC {$limit}", $where_arr), $options );
    }
    public function getItems($id, $options=array()){
        $sth = $this->db->prepare("SELECT {$this->i_select} FROM {$this->i_table} WHERE pi.item_id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convertItem( $sth->fetch( PDO::FETCH_ASSOC ) , $options )
            : array();
    }
    public function buildFragItem($results, $options=array()){
    	$data = array();

    	foreach ($results as $key => $value) {
    		if( empty($value) ) continue;
    		$data[] = $this->convertItem( $value, $options );
    	}

        return $data;
    }
    public function convertItem($data, $options=array()){
        $data = $this->cut('item_', $data);
        $data['status_arr'] = $this->getItemStatus($data['status']);
    	return $data;
    }
    public function setItem($data){

        if( !empty($data['id']) ){

            $_data = array();
            foreach ($data as $key => $value) {
                if( $key == 'id' ) continue;
                $_data[$key] = $value;
            }

            $_data['item_updated'] = date("c");
            $this->db->update($this->i_objType, $_data, "item_id={$data['id']}");
        }
        else{
            $data['item_created'] = date("c");
            $this->db->insert($this->i_objType, $data);
        }
    }
    public function delItem($id){
    	$this->db->delete($this->i_objType, "item_id={$id}");
    }
    public function delItems($pid){
    	$this->db->delete($this->i_objType, "item_pallet_id={$pid}", $this->db->count($this->i_objType, "item_pallet_id={$pid}"));
    }
    public function updateAllItem($pid, $data){
        $this->db->update($this->i_objType, $data, "item_pallet_id={$pid}");
    }

    #OPTIONS FOR FORMS
    public function listsSize($id=null){

        $data = $this->db->select("SELECT s.size_id as id, s.size_name as name
            FROM products_size s
                LEFT JOIN permit_type_size_weight p ON s.size_id=p.size_id
            WHERE p.type_id=:id GROUP BY p.size_id", array(
            ':id' => $id
        ));

        return $data;
    }
    public function listsWeight( $options=array() ){

        $results = array();

        $where = '';
        $where_arr = array();

        if( !empty($options['type']) ){
            $where .= !empty($where) ? " AND " : "";
            $where .= "p.type_id=:type";
            $where_arr[':type'] = $options['type'];
        }

        if( !empty($options['size']) ){
            $where .= !empty($where) ? " AND " : "";
            $where .= "p.size_id=:size";
            $where_arr[':size'] = $options['size'];
        }

        $data = $this->db->select("SELECT w.weight_id AS id, w.weight_dw AS dw, w.weight_nw AS nw FROM products_weight w
                LEFT JOIN permit_type_size_weight p ON w.weight_id=p.weight_id
            WHERE {$where}", $where_arr);

        foreach ($data as $key => $value) {
            $results[$key] = $value;
            $dw = !empty($value['dw']) ? $value['dw'] : "-";
            $nw = !empty($value["nw"]) ? $value["nw"] : "-";
            $results[$key]['name'] = $dw.' / '.$nw;
        }

        return $results;
    }
    public function listsBeep( $id ){
    	$data = $this->db->select("SELECT breed_id AS id, breed_name AS name FROM products_breed WHERE breed_type_id=:id", array(':id'=>$id));

        return $data;
    }
    public function listsRows($id){
        $data = $this->db->select("SELECT row_id AS id, row_name AS name, row_deep AS deep FROM warehouse_rows WHERE row_ware_id=:id", array(':id'=>$id));
        return $data;
    }
    public function getRows($id){
        $data = array();

        $results = $this->db->select("SELECT row_id AS id, row_name AS name, row_deep AS deep FROM warehouse_rows WHERE row_id=:id", array(':id'=>$id));
        if( !empty($results) ) $data = $results[0];

        return $data;
    }

    #Status
    public function status(){
        $a[] = array('id'=>1, 'name'=>'ปกติ');
        $a[] = array('id'=>2, 'name'=>'Hold');
        return $a;
    }
    public function getStatus($id){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $id == $value['id'] ){
                $data = $value;
                break;
            }
        }
        return $data;
    }

    public function itemStatus(){
        $a[] = array('id'=>1, 'name'=>'On Pallet');
        $a[] = array('id'=>2, 'name'=>'Hold');
        $a[] = array('id'=>3, 'name'=>'QC');
        // $a[] = array('id'=>4, 'name'=>'Move Pallet');

        return $a;
    }
    public function getItemStatus($id){
        $data = array();
        foreach ($this->itemStatus() as $key => $value) {
            if( $id == $value['id'] ){
                $data = $value;
                break;
            }
        }
        return $data;
    }

    #Brand
    #Brand
    private $brand_select = "brand_id AS id, brand_name AS name, brand_status AS status";
    private $brand_table = "pallets_brand";
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
            $data[$key]["status_arr"] = $this->query('products')->getBrandStatus($value["status"]);
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
        $data["status_arr"] = $this->query('products')->getBrandStatus( $data["status"] );
        $data["permit"]["del"] = true;

        return $sth->rowCount()==1
            ? $data
            : array();
    }
    public function insertBrand( &$data ){
        $data["brand_status"] = "enabled";

        $this->db->insert( $this->brand_table, $data );
        $data["id"] = $this->db->lastInsertId();
    }
    public function updateBrand( $id, $data ){
        $this->db->update( $this->brand_table, $data, "`brand_id`={$id}" );
    }
    public function delBrand( $id ){
        $this->db->delete( $this->brand_table, "`brand_id`={$id}" );
    }
    public function is_brand($text){
        return $this->db->count($this->brand_table, "`brand_name`=:text", array(':text'=>$text));
    }

    #warehouse
    public function warehouse(){
        return $this->db->select("SELECT ware_id AS id, ware_name AS name FROM warehouse");
    }

    #CODe
    public function updateAutoCode($data){
        $results = $this->db->select("SELECT pallet_code FROM pallets WHERE {$this->_cutNamefield}type_id=:type AND {$this->_cutNamefield}date=:date ORDER BY pallet_id DESC LIMIT 1", array(":type"=>$data['pallet_type_id'], ":date"=>$data['pallet_date']));
        $num = empty($results) ? 1 : $results[0]['pallet_code'] + 1;
        return "{$num}";
    }

    #SUMMARY
    public function summaryHold($id){
        $sth = $this->db->prepare("SELECT SUM(hold_qty) AS total FROM hold WHERE hold_parent_id=:id");
        $sth->execute( array(
            ':id' => $id
        ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        return !empty($data['total']) ? $data['total'] : 0;
    }

    
}
