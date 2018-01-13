<?php
class Packing extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }
        else{
            if( $this->format=='json' ){
                $this->view->setData('results', $this->model->query('planload')->lists(array('status'=>1)));
                $render = 'packing/lists/json';
            }
            else{
                $render = 'packing/lists/display';
            }
        }
        $this->view->render($render);
    }
    public function add(){
        if( empty($this->me) ) $this->error();

        $this->view->setPage('on', 'packing');
        $this->view->setPage('title', 'Create en Pack');

        $planload = $this->model->query('planload')->lists( array('status'=>1, 'unlimit'=>1) );
        $this->view->setData('planload', $planload);
        $this->view->render('packing/forms/create');
    }
    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $this->view->setPage('on', 'packing');
        $this->view->setPage('title', 'Edit en Pack');

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $planload = $this->model->query('planload')->lists( array('status'=>1, 'unlimit'=>1) );
        $planload['lists'][] = $this->model->query('planload')->get($item['plan_id']);

        $this->view->setData('item', $item);
        $this->view->setData('planload', $planload);
        $this->view->render('packing/forms/create');
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
            $form   ->post('pack_plan_id')->val('is_empty')
                    ->post('pack_carton')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del($id=null){

    }
}
