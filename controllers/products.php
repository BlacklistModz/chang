<?php

class Products extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){
        $this->error();
    }

    #Product
    public function add(){

    }
    public function edit($id=null){

    }
    public function save(){

    }
    public function del($id=null){

    }

    #Type
    public function add_type(){

    	if( empty($this->me) || $this->format!="json" ) $this->error();

    	$this->view->setPage("path", "Forms/products/type");
    	$this->view->render("add_type");
    }
    public function edit_type( $id=null ){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

    	$item = $this->model->getType( $id );
    	if( empty($item) ) $this->error();

    	$this->view->setData("item", $item);
    	$this->view->setPage("path", "Forms/products/type");
    	$this->view->render("add_type");
    }
    public function save_type(){

    	$id = isset($_POST["id"]) ? $_POST["id"] : null;
    	if( empty($this->me) || empty($_POST) ) $this->error();

    	if( !empty($id) ){
    		$item = $this->model->getType( $id );
    		if( empty($item) ) $this->error();
    	}

    	try {
    		$form = new Form();
    		$form   ->post('type_code')
                    ->post('type_name')->val('is_empty')
                    ->post('type_icon')->val('is_empty');

    		$form->submit();
    		$postData = $form->fetch();

    		$has_name = true;
    		if( !empty($item) ){
    			if( $item["name"] == $postData["type_name"] ){
    				$has_name = false;
    			}
    		}

    		if( $this->model->is_typeName($postData["type_name"]) && $has_name ){
    			$arr["error"]["type_name"] = "ตรวจพบชื่อซ้ำในระบบ";
    		}

    		if( empty($arr['error']) ){

    			if( !empty($id) ){
    				$this->model->updateType( $id, $postData );
    			}
    			else{
    				$this->model->insertType( $postData );
    			}

    			$arr['url'] = 'refresh';
    			$arr['message'] = 'บันทึกเรียบร้อย !';
    		}

    	} catch (Exception $e) {
    		$arr['error'] = $this->_getError($e->getMessage());
    	}

    	echo json_encode($arr);
    }
    public function del_type( $id=null ){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

    	$item = $this->model->getType( $id );
    	if( empty($item) ) $this->error();

    	if( !empty($_POST) ){

    		if( !empty($item["permit"]['del']) ){
    			$this->model->delType( $id );
    			$arr['message'] = "ลบข้อมูลเรียบร้อย";
                $arr['url'] = "refresh";
    		}
    		else{
    			$arr['message'] = "ไม่สามารถลบข้อมูลได้";
    		}
    		echo json_encode($arr);
    	}
    	else{
    		$this->view->setData("item", $item);
    		$this->view->setPage("path", "Forms/products/type");
    		$this->view->render("del_type");
    	}
    }

    #Size
    public function add_size(){
        if( empty($this->me) || $this->format!="json" ) $this->error();

        $this->view->setPage("path", "Forms/products/size");
        $this->view->render("add_size");
    }
    public function edit_size($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

        $item = $this->model->getSize($id);
        if( empty($item) ) $this->error();

        $this->view->setData("item", $item);
        $this->view->setPage("path", "Forms/products/size");
        $this->view->render("add_size");
    }
    public function save_size(){
        if( empty($this->me) || empty($_POST) ) $this->error();
        $id = isset($_POST["id"]) ? $_POST["id"] : null;

        if( !empty($id) ){
            $item = $this->model->getSize( $id );
            if( empty($item) ) $this->error();
        }

        try{

            $form = new Form();
            $form   ->post('size_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $item["name"] == $postData["size_name"] ){
                    $has_name = false;
                }
            }

            if( $this->model->is_sizeName($postData["size_name"]) && $has_name ){
                $arr["error"]["size_name"] = "ตรวจพบชื่อซ้ำในระบบ";
            }

            if( empty($arr["error"]) ){
                if( !empty($item) ){
                    $this->model->updateSize( $id, $postData );
                }
                else{
                    $this->model->insertSize( $postData );
                }

                $arr["message"] = "บันทึกเรียบร้อย";
                $arr["url"] = "refresh";
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_size($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

        $item = $this->model->getSize( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item["permit"]["del"]) ){
                $this->model->del_size( $id );
                $arr["message"] = "ลบข้อมูลเรียบร้อย";
                $arr["url"] = "refresh";
            }
            else{
                $arr["message"] = "ไม่สามารถลบข้อมูลได้";
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData("item", $item);
            $this->view->setPage("path", "Forms/products/size");
            $this->view->render("del_size");
        }
    }

    #Brand
    public function add_brand(){
        if( empty($this->me) || $this->format!="json" ) $this->error();

        $this->view->setData("status", $this->model->brandStatus());
        $this->view->setPage("path", "Forms/products/brand");
        $this->view->render("add_brand");
    }

    public function edit_brand($id=null){

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

        $item = $this->model->getBrand( $id );
        if( empty($item) ) $this->error();

        $this->view->setData("status", $this->model->brandStatus());
        $this->view->setData("item", $item);
        $this->view->setPage("path", "Forms/products/brand");
        $this->view->render("add_brand");
    }

    public function save_brand(){

        if( empty($this->me) || empty($_POST) ) $this->error();
        $id = isset($_POST["id"]) ? $_POST["id"] : null;

        if( !empty($id) ){
            $item = $this->model->getBrand( $id );
            if( empty($item) ) $this->error();
        }

        try{

            $form = new Form();
            $form   ->post('brand_code');
            $form   ->post('brand_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            $has_code = true;
            if( !empty($item) ){
                if( $item["name"] == $postData["brand_name"] ){
                    $has_name = false;
                }
                if( $item["code"] == $postData["brand_code"] ){
                    $has_code = false;
                }
            }

            if( !empty($postData["brand_code"]) ){
                if( $this->model->is_brandCode($postData["brand_code"]) && $has_code ){
                    $arr["error"]["brand_code"] = "ตรวจพบ Code ซ้ำในระบบ";
                }
            }

            if( $this->model->is_brandName($postData["brand_name"]) && $has_name ){
                $arr["error"]["brand_name"] = "ตรวจพบชื่อซ้ำในระบบ";
            }

            if( !empty($_POST["brand_status"]) ){
                $postData["brand_status"] = $_POST["brand_status"];
            }

            if( empty($arr["error"]) ){
                if( !empty($item) ){
                    $this->model->updateBrand( $id, $postData );
                }
                else{
                    $postData["brand_status"] = "enabled";
                    $this->model->insertBrand( $postData );
                }

                $arr["message"] = "บันทึกเรียบร้อย";
                $arr["url"] = "refresh";
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }

    public function del_brand($id=null){

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!="json" ) $this->error();

        $item = $this->model->getBrand( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item["permit"]["del"]) ){
                $this->model->del_brand( $id );
                $arr["message"] = "ลบข้อมูลเรียบร้อย";
                $arr["url"] = "refresh";
            }
            else{
                $arr["message"] = "ไม่สามารถลบข้อมูลได้";
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData("item", $item);
            $this->view->setPage("path", "Forms/products/brand");
            $this->view->render("del_brand");
        }
    }

    #Can
    public function add_can(){
        if( empty($this->me) || $this->format!="json" ) $this->error();

        $this->view->setPage("path", "Forms/products/can");
        $this->view->render("add_can");
    }
    public function edit_can($id=null){

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

        $item = $this->model->getCan( $id );
        if( empty($item) ) $this->error();

        $this->view->setData("item", $item);
        $this->view->setPage("path", "Forms/products/can");
        $this->view->render("add_can");
    }
    public function save_can(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"])
              ? $_POST["id"]
              : null;

        if( !empty($id) ){
            $item = $this->model->getCan( $id );
            if( empty($item) ) $this->error();
        }

        try{

            $form = new Form();
            $form   ->post('can_code');
            $form   ->post('can_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            $has_code = true;

            if( !empty($item) ){
                if( $postData['can_name'] == $item['name'] ) $has_name = false;
                if( $postData['can_code'] == $item['code'] ) $has_name = false;
            }

            if( $this->model->is_canName($postData['can_name']) && $has_name ){
                $arr['error']['can_name'] = 'มีชนิดนี้อยู่ในระบบแล้ว';
            }
            if( !empty($postData['can_code']) ){
                if( $this->model->is_canCode($postData['can_code']) && $has_code ){
                    $arr['error']['can_code'] = 'มีโค๊ตนี้อยู่ในระบบแล้ว';
                }
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateCan($id, $postData);
                }
                else{
                    $this->model->insertCan($postData);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_can($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

        $item = $this->model->getCan( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->delCan($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData("item", $item);
            $this->view->setPage("path", "Forms/products/can");
            $this->view->render("del_can");
        }
    }

    public function add_canType(){
        if( empty($this->me) || $this->format!="json" ) $this->error();

        $this->view->setPage("path", "Forms/products/canType");
        $this->view->render("add_canType");
    }
    public function edit_canType($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getcanType($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage("path", "Forms/products/canType");
        $this->view->render("add_canType");
    }
    public function save_canType(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"])
              ? $_POST["id"]
              : null;

        if( !empty($id) ){
            $item = $this->model->getcanType( $id );
            if( empty($item) ) $this->error();
        }

        try{

            $form = new Form();
            $form   ->post('type_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;

            if( !empty($item) ){
                if( $postData['type_name'] == $item['name'] ) $has_name = false;
            }

            if( $this->model->is_canTypeName($postData['type_name']) && $has_name ){
                $arr['error']['type_name'] = 'มีชนิดนี้อยู่ในระบบแล้ว';
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updatecanType($id, $postData);
                }
                else{
                    $this->model->insertcanType($postData);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_canType($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($this->me) || empty($id) || $this->format!="json" ) $this->error();

        $item = $this->model->getcanType( $id );
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->delcanType($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData("item", $item);
            $this->view->setPage("path", "Forms/products/canType");
            $this->view->render("del_canType");
        }
    }

    #breed
    public function add_breed(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

        $this->view->setData('currType', $type);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/breed');
        $this->view->render('add_breed');
    }
    public function edit_breed($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getBreed($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setData('currType', $item['type_id']);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/breed');
        $this->view->render('add_breed');
    }
    public function save_breed(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getBreed($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('breed_type_id')->val('is_empty')
                    ->post('breed_code')
                    ->post('breed_name')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            $has_breed = true;
            if( !empty($item) ){
                if( $item['type_id'] == $postData['breed_type_id'] && $item['name'] == $postData['breed_name'] ) $has_breed = false;
            }
            if( $this->model->is_breed($postData['breed_type_id'], $postData['breed_name']) && $has_breed ){
                $arr['error']['breed_name'] = 'พบชื่อนี้อยู่ในระบบ';
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateBreed($id, $postData);
                }
                else{
                    $this->model->insertBreed($postData);
                }
            }

            $arr['message'] = 'บันทึกเรียบร้อย';
            $arr['url'] = URL.'settings/products/breed?type='.$postData['breed_type_id'];

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_breed($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getBreed($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->deleteBreed($id);
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
            $this->view->setPage('path', 'Forms/products/breed');
            $this->view->render('del_breed');
        }
    }

    #old
    public function add_old(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

        $this->view->setData('currType', $type);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/old');
        $this->view->render('add_old');
    }
    public function edit_old($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getOld($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setData('currType', $item['type_id']);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/old');
        $this->view->render('add_old');
    }
    public function save_old(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getOld($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('old_type_id')->val('is_empty')
                    ->post('old_code')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateOld($id, $postData);
                }
                else{
                    $this->model->insertOld($postData);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = URL.'settings/products/old?type='.$postData['old_type_id'];
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_old($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getOld($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->deleteOld($id);
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
            $this->view->setPage('path', 'Forms/products/old');
            $this->view->render('del_old');
        }
    }

    #grade
    public function add_grade(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

        $this->view->setData('currType', $type);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/grade');
        $this->view->render('add_grade');
    }
    public function edit_grade($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getGrade($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setData('currType', $item['type_id']);
        $this->view->setData('type', $this->model->type());
        $this->view->setPage('path', 'Forms/products/grade');
        $this->view->render('add_grade');
    }
    public function save_grade(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getGrade($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('grade_type_id')->val('is_empty')
                    ->post('grade_name')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            $has_grade = true;
            if( !empty($item) ){
                if( $item['type_id'] == $postData['grade_type_id'] && $item['name'] == $postData['grade_name'] ){
                    $has_grade = false;
                }
            }
            if( $this->model->is_grade($postData['grade_type_id'], $postData['grade_name']) && $has_grade ){
                $arr['error']['grade_name'] = 'มีชนิดนี้อยู่ในระบบแล้ว';
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateGrade($id, $postData);
                }
                else{
                    $this->model->insertGrade($postData);
                }
            }

            $arr['message'] = 'บันทึกเรียบร้อย';
            $arr['url'] = URL.'settings/products/grade?type='.$postData['grade_type_id'];

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_grade($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getGrade($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->deleteGrade($id);
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
            $this->view->setPage('path', 'Forms/products/grade');
            $this->view->render('del_grade');
        }

    }

    #Weight
    public function add_weight(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/products/weight');
        $this->view->render('add');
    }
    public function edit_weight($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json') $this->error();

        $item = $this->model->getWeight($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/products/weight');
        $this->view->render('add');
    }
    public function save_weight(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getWeight($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('weight_dw')
                    ->post('weight_nw');
            $form->submit();
            $postData = $form->fetch();

            $has = true;
            if( !empty($item) ){
                if( $item['dw'] == $postData['weight_dw'] && $item['nw'] == $postData['weight_nw'] ){
                    $has = false;
                }
            }

            if( $this->model->is_weight($postData['weight_dw'], $postData['weight_nw']) && $has ){
                $arr['error']['weight_dw'] = "DW : {$postData['weight_dw']} , NW : {$postData['weight_nw']}มีอยู่ในระบบแล้ว";
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateWeight($id, $postData);
                }
                else{
                    $this->model->insertWeight($postData);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Expcetion $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_weight($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json') $this->error();

        $item = $this->model->getWeight($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->deleteWeight($id);
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
            $this->view->setPage('path', 'Forms/products/weight');
            $this->view->render('del');
        }
    }

    public function setTypeSizeWeight($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getType($id);
        if( empty($item) ) $this->error();

        $results = $this->model->sizeWeight($id);
        if( !empty($_POST) ){

        }
        else{
            $this->view->setData('results', $results);
            $this->view->setData('size', $this->model->size());
            $this->view->setData('weight', $this->model->weight());
            $this->view->setPage('path', 'Forms/products/size_weight');
            $this->view->render('set');
        }
    }
}
