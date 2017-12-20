<?php
class Pallets extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){

    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        $item = $this->model->query('products')->getType($id);
        if( empty($item) ) $this->error();

        $this->view->setPage('title', 'ผลิต '.$item['name']);
        $this->view->setPage('on', 'pallets-'.$id);

        if( $this->format=='json' ){
            $results = $this->model->query('pallets')->lists( array('type'=>$id) );
            $this->view->setData('results', $results);
            $this->view->setData('warehouse', $this->model->warehouse());
            $render = 'pallets/lists/json';
        }
        else{
            $this->view->setData('size', $this->model->listsSize($id));
            $this->view->setData('grade', $this->model->query('products')->grade( array('type'=>$id) ));
            $this->view->setData('warehouse', $this->model->warehouse());
            $render = 'pallets/lists/display';
        }

        $this->view->setData('item', $item);
        $this->view->render( $render );
    }

    #Profile
    public function profile($id=null){

        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $hold = $this->model->query('hold')->lists( array('parent'=>$item['id'], 'cause'=>true) );

        $this->view->setPage('title', 'Pallet CODE : '.$item['code']);
        $this->view->setPage('on', 'pallets-'.$item['type_id']);

        $this->view->setData('hold', $hold);
        $this->view->setData('item', $item);
        $this->view->render('pallets/profile/display');
    }

    #Function of Ajax
    public function listsWeight($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;

        if( empty($type) || empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        echo json_encode($this->model->listsWeight( array('type'=>$type, 'size'=>$id) ));
    }
    public function listsRows($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        echo json_encode($this->model->listsRows( $id ));
    }
    public function getRows($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        echo json_encode($this->model->getRows( $id ));
    }

    #Function Manage
    public function add($type=null){
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : $type;
        #|| $this->format!='json'
        if( empty($type) || empty($this->me) ) $this->error();

        $results = $this->model->query('products')->getType($type);
        if( empty($results) ) $this->error();

        $this->view->setPage('on', 'pallets-'.$type);
        $this->view->setPage('title', 'เพิ่มพาเลท');

        $this->view->setData('types', $results);

        $this->view->setData('breed', $this->model->query('products')->breed( array('type'=>$results['id']) ));
        $this->view->setData('old', $this->model->query('products')->old( array('type'=>$results['id']) ));
        $this->view->setData('grade', $this->model->query('products')->grade( array('type'=>$type) ));
        $this->view->setData('products', $this->model->query('products')->lists( array('type'=>$type) ));
        $this->view->setData('size', $this->model->listsSize($type));
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('canType', $this->model->query('products')->canType());
        $this->view->setData('neck', $this->model->query('products')->canOptions());
        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('canBrand', $this->model->query('products')->canBrand());
        $this->view->setData('brix', $this->model->query('products')->brix());
        $this->view->setData('brand', $this->model->brand());
        $this->view->setData('warehouse', $this->model->warehouse());
        $this->view->SetData('brands', $this->model->query('products')->brand());

        $this->view->render('pallets/forms/add');
    }
    public function edit($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        #|| $this->format!='json'
        if( empty($id) || empty($this->me) ) $this->error();

        $item = $this->model->get($id, array('items'=>true));
        if( empty($item) ) $this->error();

        $results = $this->model->query('products')->getType($item['type_id']);
        if( empty($results) ) $this->error();

        $this->view->setPage('on', 'pallets-'.$item['type_id']);
        $this->view->setPage('title', 'แก้ไขพาเลท');

        $this->view->setData('item', $item);
        $this->view->setData('types', $results);

        $this->view->setData('breed', $this->model->query('products')->breed( array('type'=>$results['id']) ));
        $this->view->setData('old', $this->model->query('products')->old( array('type'=>$results['id']) ));
        $this->view->setData('grade', $this->model->query('products')->grade( array('type'=>$item['type_id']) ));
        $this->view->setData('products', $this->model->query('products')->lists( array('type'=>$item['type_id']) ));
        $this->view->setData('size', $this->model->listsSize($item['type_id']));
        $this->view->setData('can', $this->model->query('products')->can());
        $this->view->setData('canType', $this->model->query('products')->canType());
        $this->view->setData('neck', $this->model->query('products')->canOptions());
        $this->view->setData('lid', $this->model->query('products')->canLid());
        $this->view->setData('canBrand', $this->model->query('products')->canBrand());
        $this->view->setData('brix', $this->model->query('products')->brix());
        $this->view->setData('brand', $this->model->brand());
        $this->view->setData('warehouse', $this->model->warehouse());
        $this->view->SetData('brands', $this->model->query('products')->brand());

        $this->view->render('pallets/forms/add');
    }
    public function save(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->get($id, array('items'=>true));
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('pallet_type_id')
                    ->post('pallet_date')
                    ->post('pallet_delivery_code')->val('is_empty')
                    ->post('pallet_code')
                    ->post('pallet_pro_id')
                    ->post('pallet_grade_id')
                    ->post('pallet_breed_id')
                    ->post('pallet_old_id')
                    // ->post('pallet_size_id')->val('is_empty')
                    // ->post('pallet_weight_id')->val('is_empty')
                    ->post('pallet_size_id')
                    ->post('pallet_weight_id')
                    ->post('pallet_pro_brand_id')
                    // ->post('pallet_can_id')->val('is_empty')
                    ->post('pallet_can_id')
                    ->post('pallet_can_type_id')
                    // ->post('pallet_lid')->val('is_empty')
                    ->post('pallet_lid')
                    ->post('pallet_can_brand')
                    // ->post('pallet_neck')->val('is_empty')
                    ->post('pallet_neck')
                    ->post('pallet_brix_id')
                    ->post('pallet_brand_id')
                    ->post('pallet_ware_id')
                    ->post('pallet_deep')
                    ->post('pallet_floor')
                    ->post('pallet_row_id')
                    ->post('pallet_note');

            $form->submit();
            $postData = $form->fetch();

            /* if( empty($_POST['pallet_qty']) && empty($id) ){
                $arr['error']['pallet_qty'] = 'ช่องนี้เว้นว่างไว้ไม่ได้';
            } */

            if( empty($item) ){
                $postData['pallet_qty'] = $_POST['pallet_qty'];
            }

            if( !empty($postData['pallet_code']) ){
                $has_code = true;
                if( !empty($item) )
                {
                    if( $item['code'] == $postData['pallet_code'] && $item['type_id'] == $postData['pallet_type_id'] && $item['date'] == $postData['pallet_date'] ) $has_code = false;
                }

                if( $this->model->is_code($postData['pallet_code'], $postData['pallet_type_id'], $postData['pallet_date']) && $has_code ){
                    $arr['error']['pallet_code'] = 'ตรวจพบ Code นี้ซ้ำในระบบ';
                }
            }

            $has_delivery = true;
            if( !empty($item) ){
                if( $item['delivery_code'] == $postData['pallet_delivery_code'] ) $has_delivery = false;
            }
            if( $this->model->is_delivery($postData['pallet_delivery_code']) && $has_delivery ){
                $arr['error']['pallet_delivery_code'] = 'ตรวจพบ Code นี้ซ้ำในระบบ';
            }

            if( empty($arr['error']) ){

                if( !empty($id) ){
                    $this->model->update($id, $postData);

                    $data['item_date'] = $postData['pallet_date'];
                    $data['item_type_id'] = $postData['pallet_type_id'];
                    $data['item_pro_id'] = $postData['pallet_pro_id'];
                    $data['item_pro_brand_id'] = $postData['pallet_pro_brand_id'];
                    $data['item_size_id'] = $postData['pallet_size_id'];
                    $data['item_weight_id'] = $postData['pallet_weight_id'];
                    $data['item_grade_id'] = $postData['pallet_grade_id'];
                    $data['item_breed_id'] = $postData['pallet_breed_id'];
                    $data['item_old_id'] = $postData['pallet_old_id'];
                    $data['item_can_id'] = $postData['pallet_can_id'];
                    $data['item_can_type_id'] = $postData['pallet_can_type_id'];
                    $data['item_can_brand'] = $postData['pallet_can_brand'];
                    $data['item_neck'] = $postData['pallet_neck'];
                    $data['item_ware_id'] = $postData['pallet_ware_id'];
                    $data['item_deep'] = $postData['pallet_deep'];
                    $data['item_floor'] = $postData['pallet_floor'];
                    $data['item_row_id'] = $postData['pallet_row_id'];

                    $this->model->updateAllItem($id,$data);
                }
                else{
                    $this->model->insert($postData);
                    $id = $postData['id'];
                }

                if( !empty($postData['pallet_qty']) && !empty($id) ){

                    $_items = array();
                    if( !empty($item) ){
                        foreach ($item['items'] as $key => $value) {
                            $_items[] = $value;
                        }
                    }

                    for($i=1;$i<=$postData['pallet_qty'];$i++){

                        if( !empty($_items[$i]) ){
                            $data['id'] = $_items[$i]['id'];
                            unset($_items[$i]);
                        }

                        $data['item_date'] = $postData['pallet_date'];
                        $data['item_type_id'] = $postData['pallet_type_id'];
                        $data['item_pallet_id'] = $id;
                        $data['item_pro_id'] = $postData['pallet_pro_id'];
                        $data['item_pro_brand_id'] = $postData['pallet_pro_brand_id'];
                        $data['item_size_id'] = $postData['pallet_size_id'];
                        $data['item_weight_id'] = $postData['pallet_weight_id'];
                        $data['item_grade_id'] = $postData['pallet_grade_id'];
                        $data['item_breed_id'] = $postData['pallet_breed_id'];
                        $data['item_old_id'] = $postData['pallet_old_id'];
                        $data['item_can_id'] = $postData['pallet_can_id'];
                        $data['item_can_type_id'] = $postData['pallet_can_type_id'];
                        $data['item_can_brand'] = $postData['pallet_can_brand'];
                        $data['item_neck'] = $postData['pallet_neck'];
                        $data['item_ware_id'] = $postData['pallet_ware_id'];
                        $data['item_deep'] = $postData['pallet_deep'];
                        $data['item_floor'] = $postData['pallet_floor'];
                        $data['item_row_id'] = $postData['pallet_row_id'];
                        $data['item_status'] = 1;

                        $this->model->setItem($data);
                    }
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = isset($_REQUEST["url"]) ? $_REQUEST["url"] : 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id, array('items'=>true));
        if( empty($item) ) $this->error();

        $results = $this->model->query('products')->getType($item['type_id']);
        if( empty($results) ) $this->error();

        if( !empty($_POST) ){
            if( !empty($item['permit']['del']) ){
                $this->model->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อยแล้ว';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setData('type', $results);
            $this->view->render('pallets/forms/del');
        }
    }
    public function setdata($id=null, $field=null){
        if( empty($id) || empty($field) || empty($this->me) ) $this->error();

        $data['pallet_'.$field] = isset($_REQUEST['value'])? $_REQUEST['value']:'';
        $this->model->update($id, $data);

        $_data['item_'.$field] = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
        $this->model->updateAllItem($id, $_data);

        /* $arr['message'] = 'บันทึกเรียบร้อย';
        echo json_encode($arr); */
    }

    #Brand
    public function add_brand(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/pallets/');
        $this->view->render('add_brand');
    }
    public function edit_brand($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getBrand($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/pallets');
        $this->view->render('add_brand');
    }
    public function save_brand(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getBrand($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('brand_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $postData['brand_name'] == $item['name'] ) $has_name = false;
            }
            if( $this->model->is_brand($postData['brand_name']) && $has_name ){
                $arr['error']['brand_name'] = 'มีชื่อนี้อยู่ในระบบแล้ว';
            }

            if( empty($arr['error']) ){
                if( !empty($item) ){
                    $this->model->updateBrand($id, $postData);
                }
                else{
                    $postData['brand_status'] = 'enabled';
                    $this->model->insertBrand($postData);
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_brand($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getBrand($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->delBrand($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อยแล้ว';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/pallets');
            $this->view->render('del_brand');
        }
    }

    #retort
    public function add_retort(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/pallets/');
        $this->view->render('add_retort');
    }
    public function edit_retort($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getRetort($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/pallets');
        $this->view->render('add_retort');
    }
    public function save_retort(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getRetort($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('rt_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $postData['tr_name'] == $item['name'] ) $has_name = false;
            }
            // if( $this->model->is_brand($postData['rt_name']) && $has_name ){
            //     $arr['error']['brand_name'] = 'มีชื่อนี้อยู่ในระบบแล้ว';
            // }

            if( empty($arr['error']) ){
                if( !empty($item) ){
                    $this->model->updateRetort($id, $postData);
                }
                else{
                //     $postData['brand_status'] = 'enabled';
                    $this->model->insertRetort($postData);
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_retort($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getRetort($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            if( !empty($item['permit']['del']) ){
                $this->model->delRetort($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อยแล้ว';
                $arr['url'] = 'refresh';
            }
            else{
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/pallets');
            $this->view->render('del_retort');
        }
    }

}
