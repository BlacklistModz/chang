<?php 
class job_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objName = "job_orders";
    private $_table = "job_orders";
    private $_field = "*";
    private $_cutNamefield = "job_";

    public function insert( &$data ){
    	$this->db->insert( $this->_objName, $data);
        $data["{$this->_cutNamefield}"] = $this->db->lastInsertId();
    }
    public function update($id, $data) {
        $this->db->update( $this->_objName, $data, "`{$this->_cutNamefield}id`={$id}" );
    }
    public function delete($id){
    	$this->db->delete( $this->_objName, "`{$this->_cutNamefield}id`={$id}" );
    }
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

        $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

        $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
        $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
        $limit = empty($options['unlimit'])? $this->limited( $options['limit'], $options['pager'] ): '';

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
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

    	if( !empty($options['items']) ){
    		$data['items'] = $this->listsItems($data['id']);
    	}

    	return $data;
    }

    #ITEMS
    public function listsItems($id){
    	return $this->buildFragItem( $this->db->select("SELECT * FROM job_orders_items WHERE item_job_id=:id", array(":id"=>$id)) );
    }
    public function buildFragItem($results){
    	$data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convertItem($results);
        }

        return $data;
    }
    public function convertItem($data){
    	$data = $this->cut('item_', $data);

    	return $data;
    }

    #STATUS
    public function status(){
        $a[] = array('id'=>1, 'name'=>'รอการตรวจสอบ');
        $a[] = array('id'=>2, 'name'=>'ยืนยัน');
        $a[] = array('id'=>3, 'name'=>'ยกเลิก');

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

    #Pack
    public function pack(){
        $a[] = array('id'=>6, 'name'=>6);
        $a[] = array('id'=>12, 'name'=>12);
        $a[] = array('id'=>24, 'name'=>24);

        return $a;
    }
    public function getPack($id){
        $data = array();
        foreach ($this->pack() as $key => $value) {
            if( $value['id'] == $id ){
                $data = $value;
                break;
            }
        }
        return $data;
    }
}