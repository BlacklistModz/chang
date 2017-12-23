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
                $this->view->setData('results', $this->model->lists());
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

        $planload = $this->model->query('planload')->lists( array('status'=>1, 'unlimit'=>1) );
        $this->view->setData('planload', $planload);
        $this->view->render('packing/forms/create');
    }
    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

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
    }
    public function del($id=null){
    	
    }
}
