<?php
class Brands extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add() {
    	if( empty($this->me) ) $this->error();

      $this->view->setData('status', $this->model->status());
      $this->view->setData('name', $this->model->query('brands')->lists());

      $this->view->setPage('path','Forms/customers_brands');
      $this->view->render("add");
    }
    public function edit( $id=null ){
      $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
      if( empty($this->me) || empty($id) ) $this->error();

      $item = $this->model->get( $id );

      if( empty($item) ) $this->error();

      $this->view->setData('status', $this->model->status());
      $this->view->setData('name', $this->model->query('brands')->lists());
      // $this->view->setData('branch', $this->model->branch());
      // $this->view->setData('country', $this->model->query('system')->country());
      // $this->view->setData('currency', $this->model->currency());
      // $this->view->setData('group', $this->model->group());

      $this->view->setData('item', $item );

      // print_r($item);die;

      // $this->view->setData('prefixName', $this->model->query('system')->prefixName());
      $this->view->setPage('path','Forms/customers_brands');
      $this->view->render("add");
    }
    public function save() {
    	if( empty($this->me) || empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id, array('options'=> true));
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('name')->val('is_empty')
                    ->post('status')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->update( $id, $postData );
                }
                else{
                    $this->model->insert( $postData );
                }

                $arr['message'] = 'บันทึกเรียบร้อยแล้ว';
                $arr['url'] = URL.'settings/accounts/customers_brands';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }


        echo json_encode($arr);
    }
    public function del($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

          $this->model->delete($id);

            // if ($item['permit']['del']) {
            //     $arr['message'] = 'Already Removed.';
            // } else {
            //     $arr['message'] = 'Data can not deleted.';
            // }

            if( isset($_REQUEST['callback']) ){
                // $arr['callback'] = $_REQUEST['callback'];
                $arr['data'] = $id;
            }

            $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : 'refresh';

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/customers_brands');
            $this->view->render("del");
        }
    }

}
