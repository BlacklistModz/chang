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

        $item = $this->model->get($id, array('summary'=>true));
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
        $this->view->setData('brands', $this->model->query('products')->brand());
        $this->view->setData('batch', $this->model->batch());
        $this->view->setData('retort', $this->model->retort());

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
        $this->view->setData('brands', $this->model->query('products')->brand());
        $this->view->setData('batch', $this->model->batch());
        $this->view->setData('retort', $this->model->retort());

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

            // if( empty($item) ){
            //     $postData['pallet_qty'] = $_POST['pallet_qty'];
            // }

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
                    $postData['pallet_emp_id'] = $this->me['id'];
                    $this->model->insert($postData);
                    $id = $postData['id'];
                }

                $postRetort = array();
                for($i=0;$i<=count($_POST["retort"]["id"]);$i++){
                    if( empty($_POST["retort"]["id"][$i]) || empty($_POST["retort"]["batch"][$i]) || empty($_POST["retort"]["qty"][$i]) ) continue;

                    $postRetort[] = array(
                        'prt_rt_id'=>$_POST["retort"]["id"][$i],
                        'prt_batch'=>$_POST["retort"]["batch"][$i],
                        'prt_qty'=>$_POST["retort"]["qty"][$i],
                        'prt_hr'=>$_POST["retort"]["hr"][$i],
                        'prt_min'=>$_POST["retort"]["min"][$i]
                    );
                }

                $pallet_qty = 0;

                if( !empty($postRetort) && !empty($id) ){

                    $_items = array();
                    if( !empty($item) ){
                        foreach ($item['items'] as $key => $value) {
                            $_items[] = $value['id'];
                        }
                    }

                    $_retort = array();
                    if( !empty($item['retort']) ){
                        foreach ($item['retort'] as $key => $value) {
                            $_retort[] = $value['id'];
                        }
                    }

                    $c=0;
                    foreach ($postRetort as $key => $value) {
                        for($i=1;$i<=$value["prt_qty"];$i++){

                            $data = array(
                                'item_date'=>$postData['pallet_date'],
                                'item_type_id'=>$postData['pallet_type_id'],
                                'item_pallet_id'=>$id,
                                'item_pro_id'=>$postData['pallet_pro_id'],
                                'item_pro_brand_id'=>$postData['pallet_pro_brand_id'],
                                'item_size_id'=>$postData['pallet_size_id'],
                                'item_weight_id'=>$postData['pallet_weight_id'],
                                'item_grade_id'=>$postData['pallet_grade_id'],
                                'item_breed_id'=>$postData['pallet_breed_id'],
                                'item_old_id'=>$postData['pallet_old_id'],
                                'item_can_id'=>$postData['pallet_can_id'],
                                'item_can_type_id'=>$postData['pallet_can_type_id'],
                                'item_can_brand' => $postData['pallet_can_brand'],
                                'item_neck' => $postData['pallet_neck'],
                                'item_ware_id' => $postData['pallet_ware_id'],
                                'item_deep' => $postData['pallet_deep'],
                                'item_floor' => $postData['pallet_floor'],
                                'item_row_id' => $postData['pallet_row_id'],
                                'item_rt_id' => $value['prt_rt_id'],
                                'item_batch' => $value['prt_batch'],
                                'item_status' => 1
                            );

                            if( !empty($_items[$c]) ){
                                $data['id'] = $_items[$c];
                                unset($_items[$c]);
                            }

                            $this->model->setItem($data);
                            $c++;
                        }

                        $pallet_qty += $value["prt_qty"];

                        if( !empty($_retort[$key]) ){
                            $value['id'] = $_retort[$key];
                            unset($_retort[$key]);
                        }

                        $value['prt_pallet_id'] = $id;
                        $this->model->setRetort($value);
                    }
                }

                if( !empty($_items) ){
                    foreach ($_items as $key => $value) {
                        $this->model->delItem($value);
                    }
                }

                if( !empty($_retort) ){
                    foreach ($_retort as $key => $value) {
                        $this->model->unsetRetort($value);
                    }
                }

                $this->model->update($id, array('pallet_qty'=>$pallet_qty));

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
                if( $postData['rt_name'] == $item['name'] ) $has_name = false;
            }
            if( $this->model->is_retort($postData['rt_name']) && $has_name ){
                $arr['error']['rt_name'] = 'มีชื่อนี้อยู่ในระบบแล้ว';
            }

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

    public function add_check($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $this->view->setData('pallet', $item);
        $this->view->setPage('path', 'Forms/pallets');
        $this->view->render('add_check');
    }

    public function save_check(){
        if( empty($_POST) ) $this->error();

        $pallet = $this->model->get($_POST["pallet_id"]);
        if( empty($pallet) ) $this->error();

        try{
            $form = new Form();
            $form   ->post('check_qty')->val('is_empty')
                    ->post('check_remark');
            $form->submit();
            $postData = $form->fetch();

            if( $postData['check_qty'] > $pallet['qty'] ){
                $arr['error']['check_qty'] = 'ไม่สามารถระบุจำนวนเกินสินค้าใน Pallet ได้';
            }

            if( empty($arr['error']) ){

                $postData['check_pallet_id'] = $_POST["pallet_id"];
                $postData['check_emp_id'] = $this->me['id'];
                $this->model->setCheck($postData);

                $items = $this->model->listsItems($pallet['id'], array('limit'=>$postData['check_qty']));
                foreach ($items as $key => $value) {
                    $this->model->delItem($value['id']);
                }

                $this->model->update($pallet['id'], array('pallet_qty'=>$pallet['qty'] - $postData['check_qty']));

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }

    public function setFraction($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        $options = array(
            'type'=>$item['type_id'],
            'size'=>$item['size_id'],
            'weight'=>$item['weight_id'],
            'grade'=>$item['grade_id'],
            'not'=>$item['id']
        );
        $pallet = $this->model->lists( $options );

        if( !empty($_POST) ){
            for($i=0;$i<=count($_POST["pallet"]["id"]);$i++){
                if( empty($_POST["pallet"]["id"][$i]) || empty($_POST["pallet"]["qty"][$i]) ) continue;
                $postData[] = array(
                    'frac_pallet_id' => $item['id'],
                    'frac_old_pallet_id' => $_POST["pallet"]["id"][$i],
                    'frac_qty' => $_POST["pallet"]["qty"][$i]
                );
            }

            if( empty($postData) ) $arr['error']['lists'] = 'กรุณาเลือกอย่างน้อย 1 Pallet';

            if( empty($arr['error']) ){

                $total_qty = 0;

                foreach ($postData as $key => $value) {
                    $_items = $this->model->listsItems($value['frac_old_pallet_id'], array('status'=>1, 'limit'=>$value['frac_qty']));

                    foreach ($_items as $i => $val) {
                        $data = array(
                            'id'=>$val['id'],
                            'item_pallet_id'=>$item['id']
                        );
                        $this->model->setItem($data);
                    }

                    $_pallet = $this->model->get($value['frac_old_pallet_id']);
                    if( empty($_pallet['qty']) ) continue;

                    if( $_pallet['qty'] < $value['frac_qty'] ){
                        $value['frac_qty'] = $_pallet['qty'];
                        $this->model->delete($value['frac_old_pallet_id']);
                    }
                    elseif( $_pallet['qty'] == $value['frac_qty'] ){
                        $this->model->delete($value['frac_old_pallet_id']);
                    }
                    else{
                        $this->model->update($value['frac_old_pallet_id'], array('pallet_qty'=>$_pallet['qty'] - $value['frac_qty']));
                    }
                    $total_qty += $value['frac_qty'];

                    $value['frac_old_pallet_code'] = $_pallet['code'];
                    $value['frac_date'] = date("c");
                    $value['frac_emp_id'] = $this->me['id'];
                    $this->model->setFraction($value);
                }

                $this->model->update($item['id'], array('pallet_qty'=>$item['qty'] + $total_qty));

                $arr['message'] = 'รวมเศษเรียบร้อย';
                $arr['url'] = 'refresh';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('pallet', $pallet);
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/pallets');
            $this->view->render('set_fraction');
        }
    }

    public function set_item($id=null, $status=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : $status;

        if( empty($id) || empty($status) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            if( empty($_POST["qty"]) ) $arr['error']['qty'] = 'กรุณาระบุจำนวน';
            if( $_POST["qty"] > $item["qty"] ) $arr['error']['qty'] = 'ไม่สามารถระบุจำนวนเกินสินค้าในพาเลทได้';

            if( empty($arr['error']) ){
                $_items = $this->model->listsItems($id, array("limit"=>$_POST["qty"], 'status'=>1));
                foreach ($_items as $key => $value) {
                    $data = array(
                        'id'=>$value['id'],
                        'item_status'=>$status
                    );
                    $this->model->setItem($data);
                }

                $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
                $arr['url'] = 'refresh';
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setData('status', $this->model->getItemStatus($status));
            $this->view->setPage('path', 'Forms/pallets');
            $this->view->render('set_item');
        }
    }
}
