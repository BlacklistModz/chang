<?php
class warehouse_Model extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  private $_objType = "warehouse";
  private $_table = "warehouse";
  private $_field = "*";
  private $_cutNamefield = "ware_";

  public function lists( $options=array() ){

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

    $arr['total'] = $this->db->count($this->_table, $where_str, $where_arr);

    $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
    $orderby = $this->orderby( $this->_cutNamefield.$options['sort'], $options['dir'] );
    $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );
    $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$this->_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

    if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
    $arr['options'] = $options;

    return $arr;
  }
  public function buildFrag($results, $options=array()) {
    $data = array();
    foreach ($results as $key => $value) {
      if( empty($value) ) continue;
      $data[] = $this->convert( $value, $options );
    }

    return $data;
  }
  public function get($id, $options=array()){

    $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_cutNamefield}id=:id LIMIT 1");
    $sth->execute( array(
    ':id' => $id
    ) );

    if( $sth->rowCount()==1 ){
      return $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options );
    } return array();
  }
  public function convert($data, $options=array()){

    $data = $this->cut($this->_cutNamefield, $data);
    $data['row_total'] = $this->db->count('warehouse_rows', "row_ware_id={$data['id']}");

    $data['permit']['del'] = true;
    if( !empty($data['row_total']) ){
      $data['permit']['del'] = false;
    }

    if( !empty($options['rows']) ){
      $data['rows'] = $this->listsRows( array('ware'=>$data['id'],'unlimit'=>true, 'dir'=>'ASC' ,'sort' =>'id') );
    }
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
    $this->delRows($id);
  }
  public function is_name($text){
    $text = trim($text);
    return $this->db->count($this->_objType, "{$this->_cutNamefield}name=:text", array(":text"=>$text));
  }

  #ROW
  private $r_objType = "warehouse_rows";
  private $r_table = "warehouse_rows r LEFT JOIN warehouse w ON r.row_ware_id=w.ware_id";
  private $r_field = "r.*, w.ware_name";
  private $r_cutNamefield = "row_";

  public function listsRows( $options=array() ){
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

    if( isset($_REQUEST["ware"]) ){
      $options["ware"] = $_REQUEST["ware"];
    }
    if( !empty($options["ware"]) ){
      $where_str .= !empty($where_str) ? " AND " : "";
      $where_str .= "{$this->r_cutNamefield}ware_id=:ware";
      $where_arr[":ware"] = $options["ware"];
    }

    if( !empty($options['q']) ){

      $where_str .= !empty($where_str) ? " AND " : "";
      $where_str .= "{$this->r_cutNamefield}name LIKE :q";
      $where_arr[":q"] = $options["q"];

      // $q = explode(' ', $options['q']);
      // $wq = '';
      // foreach ($q as $key => $value) {
      //     $wq .= !empty( $wq ) ? " OR ":'';
      //     $wq .= "r.row_name LIKE :q{$key}";listsRows
      //     $where_arr[":q{$key}"] = "%{$value}%";
      //     $where_arr[":f{$key}"] = $value;
      // }

      // if( !empty($wq) ){
      //     $where_str .= !empty( $where_str ) ? " AND ":'';
      //     $where_str .= "($wq)";
      // }
    }

    $arr['total'] = $this->db->count($this->r_table, $where_str, $where_arr);

    $where_str = !empty($where_str) ? "WHERE {$where_str}":'';
    $orderby = $this->orderby( $this->r_cutNamefield.$options['sort'], $options['dir'] );
    $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );

    $arr['lists'] = $this->buildFragRows( $this->db->select("SELECT {$this->r_field} FROM {$this->r_table} {$where_str} {$orderby} {$limit}", $where_arr ), $options );

    if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
    $arr['options'] = $options;

    return $arr;
  }
  public function buildFragRows( $results, $options=array() ){
    $data = array();

    foreach ($results as $key => $value) {
      if( empty($value) ) continue;
      $data[] = $this->convertRows( $value, $options );
    }

    return $data;
  }
  public function getRows( $id, $options=array() ){
    $sth = $this->db->prepare("SELECT {$this->r_field} FROM {$this->r_table} WHERE {$this->r_cutNamefield}id=:id LIMIT 1");
    $sth->execute( array(
    ':id' => $id
    ) );

    if( $sth->rowCount()==1 ){
      return $this->convertRows( $sth->fetch( PDO::FETCH_ASSOC ), $options );
    } return array();
  }
  public function convertRows( $data, $options=array() ){

    $data = $this->cut($this->r_cutNamefield, $data);

    if( !empty($options["pallets"]) ){
      $data['pallets'] = $this->listsPallets($data['id']);
    }

    $data['permit']['del'] = true;

    return $data;
  }
  public function insertRows(&$data){
    $data["{$this->r_cutNamefield}created"] = date("c");
    $data["{$this->r_cutNamefield}updated"] = date("c");

    $this->db->insert($this->r_objType, $data);
    $data["id"] = $this->db->lastInsertId();
  }
  public function updateRows($id, $data){
    $data["{$this->r_cutNamefield}updated"] = date("c");
    $this->db->update($this->r_objType, $data, "{$this->r_cutNamefield}id={$id}");
  }
  public function deleteRows($id){
    $this->db->delete($this->r_objType, "{$this->r_cutNamefield}id={$id}");
  }
  public function is_rows($text, $ware){
    $text = trim($text);
    return $this->db->count($this->r_objType, "{$this->r_cutNamefield}name=:text AND {$this->r_cutNamefield}ware_id=:ware", array(":text"=>$text, ":ware"=>$ware));
  }
  #Delete All Rows
  public function delRows($wid){
    $this->db->delete($this->r_objType, "{$this->r_cutNamefield}ware_id={$wid}", $this->db->count($this->r_objType, "{$this->r_cutNamefield}ware_id={$wid}"));
  }

  #listPallets By Rows
  public function listsPallets($id){
    $data = array();
    $results = $this->query('pallets')->buildFrag($this->db->select("SELECT * FROM pallets WHERE pallet_row_id={$id}"));
    foreach ($results as $key => $value) {
      if( empty($value['deep']) || empty($value['floor']) ) continue;
      $data[$value['deep']][$value['floor']] = $value;
      $data[$value['deep']][$value['floor']]['icon'] = $this->getfuritpallet($value['id']);
    }
    return $data;
  }

  public function getfuritpallet($id){

      return $this->db->select("SELECT item_type_id ,type_code,type_name,type_icon FROM pallets_items p LEFT JOIN products_types t ON p.item_type_id=t.type_id  WHERE item_pallet_id = {$id} GROUP BY item_type_id");

  }
}
