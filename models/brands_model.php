<?php

class Brands_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "brands";
    private $_table = "brands";
    private $_field = "*";
    private $_cutNamefield = "brand_";


    private function _modifyData($data){
        $_data = array();
        foreach ($data as $key => $value) {
            $_data[ $this->_cutNamefield.$key ] = trim($value);
        }

        return $_data;
    }
    public function insert(&$data) {

        $this->db->insert($this->_objType, $this->_modifyData( $data ) );
        $data[$this->_cutNamefield.'id'] = $this->db->lastInsertId();
        $data = $this->convert($data);
    }
    public function update($id, $data) {
        $this->db->update($this->_objType, $this->_modifyData($data), "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id) {
        $this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
    }

    public function lists( $options=array() ) {
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'id',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();


// search
//////////////////////////////////////////////////////////////////////////////
    if( !empty($options['q']) ){
        $where_str .= !empty($where_str) ? " AND " : "";
        $where_str .= "brand_name LIKE :q";
        $where_arr[":q"] = "%{$options["q"]}%";

    }
//////////////////////////////////////////////////////////////////////////////
if (isset($_REQUEST['status'])) {
  $options['status'] = $_REQUEST['status'];
}
if( !empty($options['status']) ){
    $where_str .= !empty($where_str) ? " AND " : "";
    $where_str .= "brand_status = :status";
    $where_arr[":status"] = $options["status"];

}

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );
        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function get($id){

        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        if( $sth->rowCount()==1 ){
            return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) );
        } return array();
    }
    public function convert($data){
        $data = $this->cut($this->_cutNamefield, $data);
        $data['status'] = $this->getStatus($data['status']);

        return $data;
    }

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

}
