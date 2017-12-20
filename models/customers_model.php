<?php

class customers_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "customers";
    private $_table = "customers";
    private $_field = "*";
    private $_cutNamefield = "cus_";

    public function insert(&$data) {
      $data["{$this->_cutNamefield}created"] = date("c");
      $data["{$this->_cutNamefield}updated"] = date("c");
      // print_r($data);die;
      $this->db->insert( $this->_objName, $data );
      $data["{$this->_cutNamefield}id"] = $this->db->lastInsertId();
    }

    public function update($id, $data) {
      $data["{$this->_cutNamefield}updated"] = date("c");
      $this->db->update( $this->_objName, $data, "`{$this->_cutNamefield}id`={$id}" );
    }
    public function delete($id) {
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
    public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,


            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:'',

            'more' => true
        ), $options);

        if( isset($_REQUEST['view_stype']) ){
            $options['view_stype'] = $_REQUEST['view_stype'];
        }

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        $groupby = "";

        if( isset($options['not']) ){
            $where_str .= !empty( $where_str ) ? " AND ":'';
            $where_str = "{$this->_cutNamefield}id!=:not";
            $where_arr[':not'] = $options['not'];
        }
        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "cus_id LIKE :q{$key} OR cus_name LIKE :q{$key} OR cus_contact_phone LIKE :q{$key} OR cus_contact_fax LIKE :q{$key} OR
                     cus_contact_email LIKE :q{$key}";
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
        $limit = $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$groupby} {$orderby}  {$limit}", $where_arr ), $options );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
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

        return $data;
    }

    #GROUP
    public function group(){
        $a[] = array('id'=>'A', 'name'=>'GROUP A', 'detail'=>'UP TO USD 200,000');
        $a[] = array('id'=>'B', 'name'=>'GROUP B', 'detail'=>'100,000-200,000');
        $a[] = array('id'=>'C', 'name'=>'GROUP C', 'detail'=>'LOWER USD 100,000');

        return $a;
    }
    public function getGroup($id){
        $data = array();
        foreach ($this->group() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }
    #Currency
    public function currency(){
        $a[] = array('id'=>'USD', 'name'=>'USD');
        $a[] = array('id'=>'EUR', 'name'=>'EUR');
        $a[] = array('id'=>'THB', 'name'=>'THB');

        return $a;
    }
    public function getCurrency($id){
        $data = array();
        foreach ($this->currency() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }
    #Status
    public function status(){
        $a[] = array('id'=>'A', 'name'=>'Active');
        $a[] = array('id'=>'I', 'name'=>'Inactive');

        return $a;
    }
    public function getStatus($id){
        $data = array();
        foreach ($this->status() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }

    #Branch
    public function branch(){
        $a[] = array('id'=>'00001', 'name'=>'00001');
        $a[] = array('id'=>'00002', 'name'=>'00002');
        $a[] = array('id'=>'00003', 'name'=>'00003');
        $a[] = array('id'=>'00004', 'name'=>'00004');
        $a[] = array('id'=>'00005', 'name'=>'00005');

        return $a;
    }
    public function getbranch($id){
        $data = array();
        foreach ($this->branch() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }

    #Brand
    public function brand(){
        $a[] = array('id'=>'A', 'name'=>'Active');
        $a[] = array('id'=>'I', 'name'=>'Inactive');

        return $a;
    }
    public function getbrand($id){
        $data = array();
        foreach ($this->brand() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }

        return $data;
    }
}
