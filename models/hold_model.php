<?php 
class hold_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private $_objType = "hold";
    private $_table = "hold ph 
    				   LEFT JOIN pallets p ON ph.hold_pallet_id=p.pallet_id
    				   LEFT JOIN pallets pl ON ph.hold_pallet_id=pl.pallet_id
    				   LEFT JOIN products_types t ON ph.hold_type_id=t.type_id
    				   LEFT JOIN employees emp ON ph.hold_emp_id=emp.emp_id";
    private $_field = "ph.*
    				   , p.pallet_date
    				   , p.pallet_code
    				   , p.pallet_delivery_code

    				   , pl.pallet_date AS old_pallet_date
    				   , pl.pallet_code AS old_pallet_code
    				   , pl.pallet_delivery_code AS old_delivery_code

    				   , emp.emp_prefix_name
    				   , emp.emp_first_name
    				   , emp.emp_last_name";
    private $_cutNamefield = "hold_";

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
        $this->delHoldCause($id);
    }

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

        $where_str = "";
        $where_arr = array();

        if( !empty($options['pallet']) ){
        	$where_str .= !empty($where_str) ? " AND " : "";
        	$where_str .= "{$this->_cutNamefield}pallet_id=:pallet";
        	$where_arr[':pallet'] = $options['pallet'];
        }

        if( !empty($options['parent']) ){
            $where_str .= !empty($where_str) ? " AND " : "";
            $where_str .= "{$this->_cutNamefield}parent_id=:parent";
            $where_arr[':parent'] = $options['parent'];
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
    public function convert($data, $options=array()){
    	$data = $this->cut($this->_cutNamefield, $data);

    	$prefix_name_str = '';
    	if( !empty($data['emp_prefix_name']) ){
    		$prefix_name_str = $this->query('system')->getPrefixName($data['emp_prefix_name']);
    	}
        $data['emp_fullname'] = "{$prefix_name_str}{$data['emp_first_name']} {$data['emp_last_name']}";

        if( !empty($options['items']) ){
        	$data['items'] = $this->listsItems($data['id']);
        }

        if( !empty($options['cause']) ){
            $data['cause'] = $this->listsCause($data['id']);
        }

        if( !empty($options['manage']) ){
            $data['manage'] = $this->listsManage($data['id']);
        }

        $data['status'] = $this->getStatus($data['status']);

        $data['permit']['del'] = true;

        return $data;
    }

    #Lists
    private $i_objType = "hold_items";
    private $i_table = "hold_items phi
    					LEFT JOIN hold ph ON phi.item_hold_id=ph.hold_id
    					LEFT JOIN products_types t ON ph.hold_type_id=t.type_id";
    private $i_field = "phi.*
    					, ph.hold_start_date
    					, ph.hold_end_date
    					, ph.hold_qty
    					, ph.hold_note

    					, t.type_name";
    private $i_cutNameField = "item_";
    public function listsItems($id, $options=array()){
    	return $this->buildFragItem( $this->db->select("SELECT {$this->i_field} FROM {$this->i_table} WHERE phi.item_hold_id=:id", array(':id'=>$id) ), $options );
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

        $data = $this->cut($this->i_cutNameField, $data);
        $data['detail'] = $this->query('pallets')->getItems($data['parent_id']);

    	return $data;
    }
    public function setItem($data){

    	if( !empty($data['id']) ){

            $_data = array();
            foreach ($data as $key => $value) {
                if( $key == 'id' ) continue;
                $_data[$key] = $value;
            }

            $_data["{$this->i_cutNameField}updated"] = date("c");
            $this->db->update($this->i_objType, $_data, "{$this->i_cutNameField}id={$data['id']}");
        }
        else{
            $data["{$this->i_cutNameField}created"] = date("c");
            $this->db->insert($this->i_objType, $data);
        }
    }
    public function delItem($id){
    	$this->db->delete($this->i_objType, "{$this->i_cutNameField}id={$id}");
        $this->delGrade( $id );
    }
    public function delItems($pid){
    	$this->db->delete($this->i_objType, "{$this->i_cutNameField}hold_id={$pid}", $this->db->count($this->i_objType, "{$this->i_cutNameField}hold_id={$pid}"));
    }

    #HOLD CAUSE
    public function listsCause($id){
        return $this->db->select("SELECT c.cause_id AS id, c.cause_name AS name, p.note FROM hold_cause_permit p LEFT JOIN hold_cause c ON p.cause_id=c.cause_id WHERE p.hold_id=:id", array(':id'=>$id));
    }
    public function setCause($data){
        $this->db->insert('hold_cause_permit', $data);
    }
    public function delHoldCause($id){
        $this->db->delete('hold_cause_permit', "hold_id={$id}", $this->db->count('hold_cause_permit', "hold_id={$id}"));
    }

    #HOLD Manage
    public function listsManage($id){
        return $this->db->select("SELECT c.manage_id AS id, c.manage_name AS name, p.note FROM hold_manage_permit p LEFT JOIN hold_manage c ON p.manage_id=c.manage_id WHERE p.hold_id=:id", array(':id'=>$id));
    }
    public function setManage($data){
        $this->db->insert('hold_manage_permit', $data);
    }
    public function delHoldManage($id){
        $this->db->delete('hold_manage_permit', "hold_id={$id}", $this->db->count('hold_manage_permit', "hold_id={$id}"));
    }

    #status
    public function status(){
        $a[] = array('id'=>1, 'name'=>'HOLD');
        $a[] = array('id'=>2, 'name'=>'RELEASE');

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

    #cause
    private $c_table = "hold_cause";
    private $c_field = "cause_id AS id, cause_name AS name";
    public function cause(){
        return $this->db->select("SELECT {$this->c_field} FROM {$this->c_table} ORDER BY cause_id ASC");
    }
    public function getCause($id){
        $sth = $this->db->prepare("SELECT {$this->c_field} FROM {$this->c_table} WHERE cause_id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $sth->fetch( PDO::FETCH_ASSOC )
            : array();
    }
    public function insertCause($data){
        $this->db->insert($this->c_table, $data);
    }
    public function updateCause($id, $data){
        $this->db->update($this->c_table, $data, "cause_id={$id}");
    }
    public function deleteCause($id){
        $this->db->delete($this->c_table, "cause_id={$id}");
    }
    public function is_cause($text){
        return $this->db->count($this->c_table, "cause_name=:text", array(':text'=>$text));
    }

    #manage
    private $m_table = "hold_manage";
    private $m_field = "manage_id AS id, manage_name AS name";
    public function manage(){
        return $this->db->select("SELECT {$this->m_field} FROM {$this->m_table} ORDER BY manage_id ASC");
    }
    public function getManage(){
        $sth = $this->db->prepare("SELECT {$this->m_field} FROM {$this->m_table} WHERE manage_id=:id LIMIT 1");
        $sth->execute( array(
            ':id' => $id
        ) );

        return $sth->rowCount()==1
            ? $sth->fetch( PDO::FETCH_ASSOC )
            : array();
    }
    public function insertManage($data){
        $this->db->insert($this->m_table, $data);
    }
    public function updateManage($id, $data){
        $this->db->update($this->m_table, $data, "manage_id={$id}");
    }
    public function deleteManage($id){
        $this->db->delete($this->m_table, "manage_id={$id}");
    }
    public function is_manage($text){
        return $this->db->count($this->m_table, "manage_name=:text", array(':text'=>$text));
    }

    #import
    public function import($type, $data){

        $_data[$type.'_name'] = $data;
        if( $type == 'cause' ){
            $this->insertCause($_data);
        }
        elseif( $type == 'manage' ){
            $this->insertManage($_data);
        }
    }
}