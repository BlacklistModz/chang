<?php

class Planning extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index( $id=null ) {

        $this->view->setPage('title', 'แผนการผลิต');

    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ); $this->error();
    		$render = 'planning/profile/display';
    	}
    	else{
    		if( $this->format=='json' ){
                $this->view->setData('results', $this->model->lists());
    			$render = 'planning/lists/json';
    		}else{
    			$render = 'planning/lists/display';
    		}
    	}
    	$this->view->render($render);
    }

    public function add(){

        $this->view->setPage('on', 'planning');

        if( empty($this->me) ) $this->error();

        $this->view->setPage('title', 'เพิ่มแผนการผลิต');

        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('canOptions', $this->model->query('products')->canOptions());

        $this->view->setData('type', $this->model->query('products')->type());
        $this->view->render('planning/forms/add');
    }
    public function edit($id=null){

        $this->view->setPage('on', 'planning');

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $this->view->setPage('title', 'แก้ไขแผนการผลิต');

        $item = $this->model->get($id, array('items'=>true));
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);

        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('canOptions', $this->model->query('products')->canOptions());

        $this->view->setData('type', $this->model->query('products')->type());
        $this->view->render('planning/forms/add');
    }
    public function save(){
    	if( empty($_POST) ) $this->error();

        $id = !empty($_POST['id']) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->get($id, array('items'=>true));
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('plan_week')->val('is_empty')
                    ->post('plan_type_id')->val('is_empty')
                    ->post('plan_total_qty')
                    ->post('plan_note');

            $form->submit();
            $postData = $form->fetch();

            $weeks = $this->fn->q('time')->DayOfWeeks( $postData['plan_week'] );

            $postData['plan_start_date'] = $weeks['start'];
            $postData['plan_end_date'] = $weeks['end'];

            $postItems = $_POST['items'];

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    $postData['plan_emp_id'] = $this->me['id'];
                    $this->model->insert($postData);
                    $id = $postData['id'];
                }

                if( !empty($id) ){

                    if( !empty($item) ){
                        $this->model->delAllGrade($id);
                        $_items = array();
                        foreach ($item['items'] as $key => $value) {
                            $_items[] = $value;
                        }
                    }

                    foreach ($postItems['size'] as $key => $value) {

                        if( empty($value) ) continue;

                        $data = array();

                        if( !empty($_items[$key]) ){
                            $data['id'] = $_items[$key]['id'];
                            unset($_items[$key]);
                        }
                        
                        $data['item_plan_id'] = $id;
                        $data['item_pro_id'] = !empty($postItems['pro_id'][$key]) 
                                               ? $postItems['pro_id'][$key]
                                               : 0;

                        $data['item_size'] = $value;
                        $data['item_weight'] = $postItems['weight'][$key];
                        $data['item_lid'] = $postItems['lid'][$key];
                        $data['item_can'] = $postItems['can'][$key];
                        $data['item_neck'] = $postItems['neck'][$key];
                        $data['item_qty'] = $postItems['qty'][$key];
                        $data['item_date'] = $postItems['date'][$key];
                        $data['item_note'] = $postItems['note'][$key];

                        $this->model->setItem($data);

                        if( !empty($postItems['grade'][$key]) ){
                            foreach ($postItems['grade'][$key] as $val) {

                                $_data = array();
                                $_data['plan_id'] = $id;
                                $_data['item_id'] = $data['id'];
                                $_data['grade_id'] = $val;

                                $this->model->setGrade($_data);
                            }
                        }
                    }

                    if( !empty($_items) ){
                        foreach ($_items as $key => $value) {
                            $this->model->delItem( $value['id'] );
                        }
                    }
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = URL.'planning';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        $item = $this->model->get($id, array('items'=>true));
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->delete($id);

                foreach ($item['items'] as $val) {
                    $this->model->delGrade($val['id']);
                }

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
            $this->view->setPage('path', 'Forms/planning');
            $this->view->render('del');
        }
    }

    #Set Data Form
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
    public function listsBrix(){
        if( empty($this->me) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->query('products')->brix());
    }
    public function getType($id=null){
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();
        echo json_encode($this->model->query('products')->getType($id));
    }
}
