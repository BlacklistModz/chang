<?php 
class Job extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){

        $this->view->setPage('on', 'job');
        $this->view->setPage('title', 'Job Order');

    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

    	if( !empty($id) ){
    		$item = $this->model->get($id);
    		if( empty($item) ) $this->error();

    		$this->view->item = $item;
    		$render = "job/profile/display";
    	}
    	else{
    		if( $this->format=='json' ){
    			$this->view->results = $this->model->lists();
    			$render = "job/lists/json";
    		}
    		else{
    			$this->view->status = $this->model->status();
    			$render = "job/lists/display";
    		}
    	}
    	$this->view->render($render);
    }

    public function add(){
    	if( empty($this->me) ) $this->error();

        $this->view->setPage('on','job');

        $this->view->customers = $this->model->query('customers')->lists();
        $this->view->brands = $this->model->query('brands')->lists();
        $this->view->sales = $this->model->query('employees')->lists( array('dep'=>7) );

        $this->view->render('job/forms/create');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

        $this->view->customers = $this->model->query('customers')->lists();
        $this->view->brands = $this->model->query('brands')->lists();
        $this->view->sales = $this->model->query('employees')->lists( array('dep'=>7) );
    }
    public function save(){
    	if( empty($_POST) ) $this->error();
    }
    public function del($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

    	if( !empty($_POST) ){
    		if( !empty($item['permit']['del']) ){
    			$this->model->del($id);
    			$arr['message'] = 'ลบข้อมูลเรียบร้อย';
    			$arr['url'] = 'refresh';
    		}
    		else{
    			$arr['message'] = 'ไม่สามารถลบข้อมูลได้';
    		}
    		echo json_encode($arr);
    	}
    	else{
    		$this->view->item = $item;
    		$this->view->setPage('path','Forms/job');
    		$this->view->render('del');
    	}
    }
}