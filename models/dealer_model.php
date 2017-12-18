<?php

class Dealer_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "dealer";
    private $_table = "dealer";
    private $_field = "*";
    private $_cutNamefield = "dealer_";


    private function _modifyData($data){

        $data["updated"] = date('c'); // last update time

        $_data = array();
        foreach ($data as $key => $value) {
            $_data[ $this->_cutNamefield.$key ] = trim($value);
        }
        
        return $_data;
    }
    public function insert(&$data) {
        
        $data["created"] = date('c'); // new create time
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

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

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

        $data['paytype'] = $this->listPaytype( $data['id'] );

        return $data;
    }

    /**/
    /* Pay Type */
    /**/
    public function listPaytype( $id=null ){
        $data = $this->db->select("SELECT p.pay_id AS id , p.pay_name AS name 
            FROM system_paytype p 
                LEFT JOIN dealer_paytype_permit d ON p.pay_id=d.pay_id
            WHERE d.dealer_id=:id
        ORDER BY d.pay_id ASC" , array(':id'=>$id));

        return $data;
    }

    public function setPaytype( $data ){
        $this->db->insert('dealer_paytype_permit', $data);
    }

    public function unsetPaytype( $id=null ){
        $this->db->delete('dealer_paytype_permit', "{$this->_cutNamefield}id={$id}", $this->db->count('dealer_paytype_permit', "{$this->_cutNamefield}id={$id}") );
    }

    public function setFloor($id, $number=0){
        
        if( !is_numeric($number) ) $number = 0;
        
        
        for ($i=1; $i <= $number; $i++) { 
            $this->db->insert('rooms_floors', array(

            ));
        }
    }
}
