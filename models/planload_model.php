<?php
class planload_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    private $_objName = "planload";
    private $_table = "planload p
                       LEFT JOIN platform f ON p.plan_platform_id=f.plat_id
                       LEFT JOIN customers c ON p.plan_cus_id=c.cus_id";
    private $_field = "p.*, f.plat_name, c.cus_name";
    private $_cutNamefield = "plan_";

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

        if( !empty($options['q']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "plan_job_code LIKE :q
                           OR plan_id LIKE :q 
                           OR plan_inv LIKE :q
                           OR plan_ship LIKE :q
                           OR plan_shipper LIKE :q";
            $where_arr[":q"] = "%{$options["q"]}%";
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

        if( isset($_REQUEST["start"]) ){
            $options["status"] = $_REQUEST["status"];
        }
        if( isset($options["status"]) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}status=:status";
            $where_arr[":status"] = $options["status"];
        }

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = $this->limited( $options['limit'], $options['pager'] );
        if( !empty($options["unlimit"]) ) $limit = "";

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

        $data['name'] = $data['cus_name'].' ('.$data['job_code'].')';

        $data['plan_grade'] = $this->listsGrade($data['id']);
        $data['status'] = $this->getStatus($data['status']);
        $data['permit']['del'] = true;

        return $data;
    }

    public function status(){
    	$a[] = array('id'=>1, 'name'=>'Waiting');
    	$a[] = array('id'=>2, 'name'=>'Packed');
    	$a[] = array('id'=>3, 'name'=>'Loaded');

    	return $a;
    }
    public function getStatus($id){
    	$data = array();
    	foreach ($this->status() as $key => $value) {
    		$data = $value;
    		break;
    	}
    	return $data;
    }

    public function listsGrade($id){
        return $this->db->select("SELECT pg.*, g.grade_name FROM planload_grade pg LEFT JOIN products_grade g ON pg.grade_id=g.grade_id WHERE pg.plan_id={$id}");
    }
    public function setGrade($data){
        $this->db->insert("planload_grade", $data);
    }
    public function delAllGrade($id){
        $this->db->delete("planload_grade", "plan_id={$id}", $this->db->count("planload_grade", "plan_id={$id}"));
    }
}
