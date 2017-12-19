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

        $this->view->setData('types', $this->model->query('products')->type());
        $this->view->setData('customers', $this->model->query('customers')->lists());
        $this->view->setData('currency', $this->model->query('customers')->currency());
        $this->view->setData('brands', $this->model->query('brands')->lists());
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('pack', $this->model->pack());
        // $this->view->sales = $this->model->query('employees')->lists( array('dep'=>7) );

        $this->view->render('job/forms/create');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) ) $this->error();

    	$item = $this->model->get($id);
    	if( empty($item) ) $this->error();

        $this->view->setData('types', $this->model->query('products')->type());
        $this->view->setData('customers', $this->model->query('customers')->lists());
        $this->view->setData('currency', $this->model->query('customers')->currency());
        $this->view->setData('brands', $this->model->query('brands')->lists());
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('pack', $this->model->pack());

        $this->view->setData('item', $item);
        $this->view->render('job/forms/create');
    }
    public function save(){
    	if( empty($_POST) ) $this->error();
        print_r($_POST);die;
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

    #JSON
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
}