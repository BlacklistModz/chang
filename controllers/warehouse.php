
<?php
class Warehouse extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index($id=null) {
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

    $this->view->setPage('on', 'zone-'.$id);

    $item = $this->model->get($id, array('rows'=>1));
    if( empty($item) ) $this->error();
    // print_r($item);die;

    $this->view->setData('item', $item);
    $this->view->setData('_type', $this->model->summaryType( $id ));
    $this->view->setData('total_pallet', $this->model->summaryPallet( $id ));
    $this->view->setPage('title', 'Zone '.$item['name']);
    $this->view->render('warehouse/lists/header');
  }
  public function add(){
    if( empty($this->me) || $this->format!='json' ) $this->error();

    $this->view->setPage('path', 'Forms/warehouse');
    $this->view->render('add');
  }
  public function edit($id=null){
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    $item = $this->model->get($id);
    if( empty($item) ) $this->error();

    $this->view->setData('item', $item);
    $this->view->setPage("path", "Forms/warehouse");
    $this->view->render('add');
  }
  public function save(){
    if( empty($_POST) ) $this->error();

    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if( !empty($id) ){
      $item = $this->model->get($id);
      if( empty($item) ) $this->error();
    }

    try{
      $form = new Form();
      $form 	->post('ware_name')->val('is_empty');
      $form->submit();
      $postData = $form->fetch();

      $has_name = true;
      if( !empty($item) ){
        if( $item['name'] == $postData['ware_name'] ) $has_name = false;
      }

      if( $this->model->is_name($postData['ware_name']) && $has_name ){
        $arr['message'] = 'มีชื่อนี้อยู่ในระบบ';
      }

      if( empty($arr['error']) ){

        if( !empty($id) ){
          $this->model->update($id, $postData);
        }
        else{
          $this->model->insert($postData);
        }

        $arr['message'] = 'บันทึกเรียบร้อย';
        $arr['url'] = 'refresh';
      }

    } catch (Exception $e) {
      $arr['error'] = $this->_getError($e->getMessage());
    }

    echo json_encode($arr);
  }
  public function del($id=null){
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    $item = $this->model->get($id);
    if( empty($item) ) $this->error();

    if( !empty($_POST) ){
      if( !empty($item['permit']['true']) ){
        $this->model->delete($id);
        $arr['message'] = 'ลบข้อมูลเรียบร้อย';
        $arr['url'] = 'refresh';
      }
      else{
        $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
      }
      echo json_encode($arr);
    }
    else{
      $this->view->setData('item', $item);
      $this->view->setPage("path", "Forms/warehouse");
      $this->view->render('del');
    }
  }

  #Rows
  public function add_rows(){
    if( empty($this->me) || $this->format!='json' ) $this->error();

    $ware = isset($_REQUEST["ware"]) ? $_REQUEST["ware"] : null;

    $this->view->setData('currWare', $ware);
    $this->view->setData('warehouse', $this->model->lists( array('dir'=>"ASC") ));
    $this->view->setPage('path', 'Forms/warehouse/rows');
    $this->view->render('add');
  }
  public function edit_rows($id=null){
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    $item = $this->model->getRows($id);
    if( empty($item) ) $this->error();

    $this->view->setData('item', $item);
    $this->view->setData('warehouse', $this->model->lists( array('dir'=>"ASC") ));
    $this->view->setPage('path', 'Forms/warehouse/rows');
    $this->view->render('add');
  }
  public function save_rows(){
    if( empty($_POST) ) $this->error();

    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if( !empty($id) ){
      $item = $this->model->getRows($id);
      if( empty($item) ) $this->error();
    }

    try{
      $form = new Form();
      $form 	->post('row_ware_id')->val('is_empty')
      ->post('row_name')->val('is_empty')
      ->post('row_deep')->val('is_empty');
      $form->submit();
      $postData = $form->fetch();

      $has_name = true;
      if( !empty($item) ){
        if( $item['name'] == $postData['row_name'] && $item['ware_id'] == $postData['row_ware_id'] ) $has_name = false;
      }
      if( $this->model->is_rows( $postData['row_name'], $postData['row_ware_id'] ) && $has_name ){
        $arr['error']['row_name'] = 'มีชื่อนี้อยู่ในระบบ';
      }

      if( empty($arr['error']) ){
        if( !empty($id) ){
          $this->model->updateRows($id, $postData);
        }
        else{
          $this->model->insertRows($postData);
        }

        $arr['message'] = 'บันทึกเรียบร้อย';
        $arr['url'] = URL.'settings/warehouse/rows?ware='.$postData['row_ware_id'];
      }

    } catch (Exception $e) {
      $arr['error'] = $this->_getError($e->getMessage());
    }
    echo json_encode($arr);
  }
  public function del_rows($id=null){
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    $item = $this->model->getRows($id);
    if( empty($item) ) $this->error();

    if( !empty($_POST) ){

      if( !empty($item['permit']['del']) ){
        $this->model->deleteRows($id);
        $arr['message'] = 'ลบข้อมูลเรียบร้อย';
        $arr['url'] = 'refresh';
      }
      else{
        $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
      }

      echo json_encode($arr);
    }
    else{
      $this->view->setData('item', $item);
      $this->view->setPage('path', 'Forms/warehouse/rows');
      $this->view->render('del');
    }
  }

  public function run($ware=null, $prefix="A", $start=1, $end=30){

    $ware = isset($_REQUEST["ware"]) ? $_REQUEST["ware"] : $ware;
    $prefix = isset($_REQUEST["prefix"]) ? $_REQUEST["prefix"] : $prefix;
    $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : $start;
    $end = isset($_REQUEST["end"]) ? $_REQUEST["end"] : $end;

    if( empty($ware) ) $this->error();

    for ($i=$start; $i <= $end ; $i++) {
      $data = array();
      $data['row_ware_id'] = $ware;
      $data['row_name'] = "{$prefix}{$i}";
      $data['row_deep'] = 5;
      $this->model->insertRows( $data );
    }

    echo "เพิ่มแถวที่ {$prefix}{$start} ถึง {$prefix}{$end} ให้กับโกดังไอดี {$ware} เรียบร้อย";
  }

  public function showRow($id=null){
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    if( empty($id) || empty($this->me) ) $this->error();

    $item = $this->model->getRows($id, array('pallets'=>true));
    // print_r($item);die;
    if( empty($item) ) $this->error();

    $this->view->setData('item', $item);
    // $this->view->setPage('path', 'Forms/warehouse/rows');
    $this->view->render('warehouse/profile/pallet');


  }

}
