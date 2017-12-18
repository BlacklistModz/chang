<?php

class Planning_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "planning";
    private $_table = "planning p
                       LEFT JOIN products_types t ON p.plan_type_id=t.type_id
    				   LEFT JOIN employees e ON p.plan_emp_id=e.emp_id";
    private $_field = "p.*, 
                       t.type_code,
                       t.type_name,
    				   e.emp_prefix_name,
    				   e.emp_first_name,
    				   e.emp_last_name";
    private $_cutNamefield = "plan_";

    public function lists( $options=array() ){
    	$options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'updated',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            'q' => isset($_REQUEST['q'])? $_REQUEST['q']:null,

            'more' => true
        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        if( isset($_REQUEST['view_stype']) ){
            $options['view_stype'] = $_REQUEST['view_stype'];
        }

        $where_str = "";
        $where_arr = array();

        if( !empty($options['q']) ){

            $arrQ = explode(' ', $options['q']);
            $wq = '';
            foreach ($arrQ as $key => $value) {
                $wq .= !empty( $wq ) ? " OR ":'';
                $wq .= "i.manu_code=:q{$key}";
                $where_arr[":q{$key}"] = "%{$value}%";
                $where_arr[":s{$key}"] = "{$value}%";
                $where_arr[":f{$key}"] = $value;
            }

            if( !empty($wq) ){
                $where_str .= !empty( $where_str ) ? " AND ":'';
                $where_str .= "($wq)";
            }
        }

        if( isset($_REQUEST["week"]) ){
            $options["week"] = $_REQUEST["week"];
        }
        if( !empty($options["week"]) ){

            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "plan_week=:week";
            $where_arr[":week"] = $options["week"];
        }

        if( isset($_REQUEST["period_start"]) && isset($_REQUEST["period_end"]) ){
            $options["period_start"] = $_REQUEST["period_start"];
            $options["period_end"] = $_REQUEST["period_end"];
        }
        if( !empty($options["period_start"]) && !empty($options["period_end"]) ){

            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "(plan_created BETWEEN :s AND :e)";
            $where_arr[":s"] = date("Y-m-d 00:00:01",strtotime($options["period_start"]));
            $where_arr[":e"] = date("Y-m-d 23:59:59",strtotime($options["period_end"]));
        }

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
    public function convert($data , $options=array()){

    	$data = $this->cut($this->_cutNamefield, $data);
    	$prefix_name_str = $this->query('system')->getPrefixName($data['emp_prefix_name']);
        if( empty($prefix_name_str) ){
            $prefix_name_str = '';
        }
        $data['emp_fullname'] = "{$prefix_name_str}{$data['emp_first_name']} {$data['emp_last_name']}";

        if( !empty($options['items']) ){
            $data['items'] = $this->listsItems($data['id']);
        }

        $data['permit']['del'] = true;

        return $data;
    }
    public function insert(&$data){
        
        $data["{$this->_cutNamefield}created"] = date("c");
        $data["{$this->_cutNamefield}updated"] = date("c");

    	$this->db->insert($this->_objType, $data);
    	$data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data){
        $data["{$this->_cutNamefield}updated"] = date("c");
    	$this->db->update($this->_objType, $data, "{$this->_cutNamefield}id={$id}");
    }
    public function delete($id){
    	$this->db->delete($this->_objType, "{$this->_cutNamefield}id={$id}");
        $this->delItems($id);
    }

    #List
    private $i_objType = 'planning_items';
    private $i_select = "pi.*,p.plan_code,pt.pro_code,ps.size_name,pt.pro_amount";
    private $i_table = "planning_items pi 
    					LEFT JOIN planning p ON pi.item_plan_id=p.plan_id
    					LEFT JOIN products pt ON pi.item_pro_id=pt.pro_id
    					LEFT JOIN products_size ps ON pi.item_size=ps.size_id";
    public function listsItems($id, $options=array()) {
    	return $this->buildFragItem( $this->db->select("SELECT {$this->i_select} FROM {$this->i_table} WHERE pi.item_plan_id=:id", array(':id'=>$id) ), $options );
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
        $data['grade'] = $this->listsItemsGrade( $data['id'] );

    	return $data;
    }
    public function setItem(&$data){

        if( !empty($data['id']) ){

            $_data = array();
            foreach ($data as $key => $value) {
                if( $key == 'id' ) continue;
                $_data[$key] = $value;
            }

            $this->db->update($this->i_objType, $_data, "item_id={$data['id']}");
        }
        else{
            $this->db->insert($this->i_objType, $data);
            $data['id'] = $this->db->lastInsertId();
        }
    }
    public function delItem($id){
    	$this->db->delete($this->i_objType, "item_id={$id}");
        $this->delGrade( $id );
    }
    public function delItems($pid){
    	$this->db->delete($this->i_objType, "item_plan_id={$pid}", $this->db->count($this->i_objType, "item_plan_id={$pid}"));
    }
    public function listsItemsGrade($id){

        $data = $this->db->select("SELECT g.grade_id as id, g.grade_name as name 
            FROM products_grade g 
                LEFT JOIN planning_items_grade j ON g.grade_id=j.grade_id
            WHERE j.item_id=:id", array(
            ':id' => $id
        ));

        return $data;
    }

    #Grade Items
    public function setGrade($data){
        $this->db->insert('planning_items_grade', $data );
    }
    public function delGrade($id){
        $this->db->delete('planning_items_grade', "item_id={$id}", $this->db->count('planning_items_grade', "item_id={$id}"));
    }
    public function delAllGrade($pid){
        $this->db->delete('planning_items_grade', "plan_id={$pid}", $this->db->count('planning_items_grade', "plan_id={$pid}"));
    }

    #List For Planning
    public function listsSize($id=null){

        $data = $this->db->select("SELECT s.size_id as id, s.size_name as name 
            FROM products_size s 
                LEFT JOIN permit_type_size_weight p ON s.size_id=p.size_id
            WHERE p.type_id=:id GROUP BY p.size_id", array(
            ':id' => $id
        ));

        return $data;
    }
    public function listsGrade($id=null){
        $data = $this->db->select("SELECT grade_id AS id, grade_name AS name FROM products_grade WHERE grade_type_id=:id", array(':id'=>$id));

        return $data;
    }
    public function listsWeight( $options=array() ){
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

        return $data;
    }
}