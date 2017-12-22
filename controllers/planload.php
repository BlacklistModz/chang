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

        // print_r($this->model->lists());die;

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

    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setPage('on', 'planload');
        $this->view->setPage('title', 'PLANLOAD');

        $this->view->setData('item', $item);
        $this->view->setData('customer', $this->model->query('customers')->lists());
        $this->view->setData('types', $this->model->query('products')->type());
        $this->view->setData('job', $this->model->query('job')->lists());
        $this->view->setData('platform', $this->model->query('platform')->lists());

        $this->view->render('planload/forms/create');
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
            $form   ->post('plan_date')
                    ->post('plan_platform_id')
                    ->post('plan_cus_id')->val('is_empty')
                    ->post('plan_type_id')->val('is_empty')
                    ->post('plan_pro_id')
                    ->post('plan_size_id')->val('is_empty')
                    ->post('plan_weight_id')->val('is_empty')
                    ->post('plan_fcl')->val('is_empty')
                    ->post('plan_carton')->val('is_empty')
                    ->post('plan_job_id')->val('is_empty')
                    ->post('plan_inv')
                    ->post('plan_cabinet_return')
                    ->post('plan_closed_date')
                    ->post('plan_cabinet_get')
                    ->post('plan_etd_date')
                    ->post('plan_ship')
                    ->post('plan_shipper')
                    ->post('plan_remark')
                    ->post('plan_approval')
                    ->post('plan_package_carton')
                    ->post('plan_package_label')
                    ->post('plan_carton_remark');
            $form->submit();
            $postData = $form->fetch();

            $job = $this->model->query('job')->get($postData['plan_job_id']);
            $postData['plan_job_code'] = $job['code'];

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    $postData['plan_status'] = 1;
                    $this->model->insert($postData);
                    $id = $postData['id'];
                }

                if( !empty($id) ){
                    if( !empty($item['grade']) ){
                        $this->model->delAllGrade($id);
                    }

                    if( !empty($_POST["plan_grade"]) ){
                        foreach ($_POST['plan_grade'] as $key => $value) {
                            $data = array(
                                'plan_id'=>$id,
                                'grade_id'=>$value
                            );
                            $this->model->setGrade($data);
                        }
                    }
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = URL.'planload';
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
            if( !empty($item['permit']['del']) ){
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
            $this->view->setPage('path', 'Forms/planload');
            $this->view->render('del');
        }
    }
}
