<?php
class packing_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    private $_objName = "packing";
    private $_table = "packing";
    private $_field = "*";
    private $_cutNamefield = "pack_";

    public function insert(&$data){

        $data["{$this->_cutNamefield}created"] = date("c");
        $data["{$this->_cutNamefield}updated"] = date("c");

    	$this->db->insert($this->_objName, $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
        $data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->update($this->_objName, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objName, "{$this->_cutNamefield}id={$id}");
        $this->delAllItem($id);
    }

    public function lists($options=array()){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'created',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $where_str = "";
        $where_arr = array();

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options  );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert($value , $options);
        }
        return $data;
    }
    public function get($id, $options=array()){

        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) , $options )
            : array();
    }
    public function convert($data, $options=array()){
        $data = $this->cut($this->_cutNamefield, $data);

        if( !empty($options["items"]) ){
            $data["items"] = $this->listsItems($data["id"]);
        }
        $data['permit']['del'] = true;

        return $data;
    }

    #LISTS
    public function listsItems($id){
        return $this->buildFragItem( $this->db->select("SELECT * FROM packing_items WHERE item_pack_id={$id}") );
    }
    public function buildFragItem($results){
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data = $this->convertItem($value);
        }
        return $data;
    }
    public function convertItem($data){
        $data = $this->cut('item_', $data);
        return $data;
    }
    public function setItem($data){
        if( !empty($data["id"]) ){
            $id = $data["id"];
            unset($data["id"]);
            $this->db->update("packing_items", $data, "item_id={$id}");
        }
        else{
            $this->db->insert("packing_items", $data);
        }
    }
    public function delItem($id){
        $this->db->delete("packing_items", "item_id={$id}");
    }
    public function delAllItem($id){
        $this->db->delete("packing_items", "item_pack_id={$id}", $this->db->count("packing_items", "item_pack_id={$id}"));
    }
}