<?php

class Planload extends Controller  {

    public function __construct() {
        parent::__construct();        
    }
    
    public function index($id=null) {
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( !empty($id) ){
    		$this->error();

    		$item = $this->model->get($id);
    		if( empty($item) ) $this->error();
    	}
    	else{
    		if( $this->format=='json' ){
    			$this->view->setData('results', $this->model->lists());
    			$render = "planload/lists/json";
    		}
    		else{
    			$render = "planload/lists/display";
    		}
    	}
    	$this->view->render($render);
    }

    public function add(){
    	if( empty($this->me) ) $this->error();

    	$this->view->setData('customer', $this->model->query('customers')->lists());
    	$this->view->setData('types', $this->model->query('products')->type());
    	$this->view->setData('job', $this->model->query('job')->lists());
    	$this->view->setData('platform', $this->model->query('platform')->lists());

    	$this->view->render('planload/forms/create');
    	// $this->view->setPage('path', 'Forms/planload');
    	// $this->view->render('add');
    }
}