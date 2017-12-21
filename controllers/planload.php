<?php

class Planload extends Controller  {

    public function __construct() {
        parent::__construct();
    }

    #JSON
    public function listsGrade($id=null){
        if( empty($this->me) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->query('planning')->listsGrade( $id ));
    }
    public function listsProducts($id=null) {
        if( empty($this->me) || $this->format!='json' ) $this->error();
        $results = $this->model->query('products')->lists( array('type'=>$id) );
        echo json_encode( $results['lists'] );
    }
    public function listsSize($id=null){
        if( empty($this->me) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->query('planning')->listsSize( $id ));
    }
    public function listsWeight($id=null, $size=null){

        $size = isset($_REQUEST["size"]) ? $_REQUEST["size"] : $size;

        if( empty($this->me) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->query('planning')->listsWeight( array('type'=>$id, 'size'=>$size) ));
    }
    #END JSON

    public function index($id=null) {
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

    	$this->view->setPage('on', 'planload');
    	$this->view->setPage('title', 'PLANLOAD');

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

    	$this->view->setPage('on', 'planload');
    	$this->view->setPage('title', 'PLANLOAD');

    	$this->view->setData('customer', $this->model->query('customers')->lists());
    	$this->view->setData('types', $this->model->query('products')->type());
    	$this->view->setData('job', $this->model->query('job')->lists());
    	$this->view->setData('platform', $this->model->query('platform')->lists());

    	$this->view->render('planload/forms/create');
    	// $this->view->setPage('path', 'Forms/planload');
    	// $this->view->render('add');
    }
}
