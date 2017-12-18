<?php 
class Hold extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
    	$this->error();
    }

    public function add($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$currPallet = $this->model->query('pallets')->get($id);
    	if( empty($currPallet) ) $this->error();

    	$pallets = $this->model->query('pallets')->lists( array('type'=>$currPallet['type_id']) );

    	$this->view->setData('currPallet', $currPallet);
    	$this->view->setData('pallets', $pallets);
        $this->view->setData('cause', $this->model->cause());
    	$this->view->setPage('path', 'Forms/hold');
    	$this->view->render('add');
    }
    public function edit($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id, array('cause'=>true));
    	if( empty($item) ) $this->error();

    	$currPallet = $this->model->query('pallets')->get($item['pallet_id']);
    	if( empty($currPallet) ) $this->error();

    	$pallets = $this->model->query('pallets')->lists( array('type'=>$currPallet['type_id']) );

    	$this->view->setData('item', $item);
    	$this->view->setData('currPallet', $currPallet);
    	$this->view->setData('pallets', $pallets);
        $this->view->setData('cause', $this->model->cause());
    	$this->view->setPage('path', 'Forms/hold');
    	$this->view->render('add');
    }
    public function save(){
    	if( empty($_POST) ) $this->error();

    	$id = isset($_POST["id"])
    		  ? $_POST["id"]
    		  : null;

    	if( !empty($id) ){
    		$item = $this->model->get($id);
    		if( empty($item) ) $this->error();
    	}

    	try{
    		$form = new Form();
    		$form 	->post('hold_pallet_id')->val('is_empty')
    				->post('hold_type_id')
    				->post('hold_parent_id')
    				->post('hold_start_date')
    				->post('hold_end_date')
    				->post('hold_note')
    				->post('hold_qty')->val('is_empty');
    		$form->submit();
    		$postData = $form->fetch();
 
    		$postData['hold_type_id'] = $_POST["hold_type_id"];
    		$postData['hold_parent_id'] = $_POST['hold_parent_id'];

    		$pallet = $this->model->query('pallets')->get($postData['hold_parent_id']);
    		if( $pallet['qty'] < $postData['hold_qty'] ){
    			$arr['error']['hold_qty'] = 'ไม่สามารถกรอกข้อมูลที่มีค่ามากกว่าจำนวนสินค้าในพาเลทได้';
    		}

            $cause = array();
            $postCause = $_POST["cause"];
            #empty($postCause['note'][$key]) ||
            foreach ($postCause["id"] as $key => $value) {
                if( empty($value) ) continue;

                $cause[$key]['cause_id'] = $value;
                $cause[$key]['note'] = $postCause['note'][$key];
            }

            if( empty($cause) ){
                $arr['error']['cause'] = 'กรุณาเลือกอย่างน้อย 1 สาเหตุ';
            }

    		if( empty($arr['error']) ){

    			if( !empty($id) ){
    				$this->model->update($id, $postData);
    			}
    			else{
    				$postData['hold_emp_id'] = $this->me['id'];
    				$postData['hold_status'] = 1;
    				$this->model->insert($postData);
    				$id = $postData['id'];
    			}

    			if( !empty($id) ){
                    #SET CAUSE
                    if( !empty($item) ){
                       $this->model->delHoldCause($id); #DEL ALL CAUSE BY HOLD 
                    }
                    foreach ($cause as $value) {
                        $data = array(
                            'hold_id'=>$id,
                            'cause_id'=>$value['cause_id'],
                            'note'=>$value['note']
                        );
                        $this->model->setCause($data);
                    }

                    #SET ITEM
    				$options = array(
    					'limit'=>$postData['hold_qty'],
    					'status'=>1
    				);
    				$items = $this->model->query('pallets')->listsItems($postData['hold_parent_id'], $options);
    				if( !empty($items) ){
    					$_pallet = $this->model->query('pallets')->get($postData['hold_pallet_id']);
    					foreach ($items as $key => $value) {
    						#INSERT TO HOLD LISTS
    						$data = array(
    							'item_hold_id'=>$id,
    							'item_parent_id'=>$value['id'],
    							'item_status'=>1
    						);
    						$this->model->setItem($data);

    						#UPDATE ITEMS ON PALLET
    						$_data = array(
    							'id'=>$value['id'],
    							'item_pallet_id'=>$postData['hold_pallet_id'],
    							'item_ware_id'=>$_pallet['ware_id'],
    							'item_floor'=>$_pallet['floor'],
    							'item_row_id'=>$_pallet['row_id'],
    							'item_deep'=>$_pallet['deep'],
    							'item_status'=>2
    						);
    						$this->model->query('pallets')->setItem($_data);
    					}

    					$qty = $pallet['qty'] - $postData['hold_qty'];
    					$this->model->query('pallets')->update($postData['hold_parent_id'], array('pallet_qty'=>$qty));

                        #GET NOW BALANCE
                        $n_pallet = $this->model->query('pallets')->get($postData['hold_pallet_id']);
    					$balance = $n_pallet['qty'] + $postData['hold_qty'];
    					$this->model->query('pallets')->update($postData['hold_pallet_id'], array('pallet_qty'=>$balance));
    				}
    			}

    			$arr['message'] = 'บันทึกเรียบร้อย';
    			$arr['url'] = 'refresh';
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

    	if( !empty($_POST) ){ 
    		if( !empty($item['permit']['del']) ){

    			foreach ($item['items'] as $key => $value) {
    				$items = array('id'=>$value['parent_id'], 'item_pallet_id'=>$item['parent_id'] ,'item_status'=>1);
    				$this->model->query('pallets')->setItem( $items );
    			}

    			$pallet = $this->model->query('pallets')->get($item['pallet_id']);
    			$this->model->query('pallets')->update($item['pallet_id'], array('pallet_qty'=>$pallet['qty'] - $item['qty']));

                $parent = $this->model->query('pallets')->get($item['parent_id']);
    			$this->model->query('pallets')->update($item['parent_id'], array('pallet_qty'=>$parent['qty'] + $item['qty']));

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
    		$this->view->setPage('path', 'Forms/hold');
    		$this->view->render('del');
    	}
    }

    public function set_hold($id=null){
    	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
    	if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

    	$item = $this->model->get($id, array('items'=>true));
    	if( empty($item) ) $this->error();

    	if( !empty($_POST) ){

            try{
                $form = new Form();
                $form   ->post('hold_manage_note');
                $form->submit();
                $postData = $form->fetch();

                $manage = $_POST["manage"];
                $_manage = array();
                foreach ($manage['id'] as $key => $value) {
                    if( empty($value) ) continue;

                    $_manage[$key]['manage_id'] = $value;
                    $_manage[$key]['note'] = $manage['note'][$key];
                }

                if( empty($_manage) ){
                    $arr['error']['manage'] = 'กรุณาเลือกอย่างน้อย 1 รายการ';
                }

                if( empty($arr['error']) ){

                    foreach ($_manage as $key => $value) {
                        $_data = array(
                            'hold_id'=>$id,
                            'manage_id'=>$value['manage_id'],
                            'note'=>$value['note']
                        );
                        $this->model->setManage($_data); #SET MANAGE
                    }

                    foreach ($item['items'] as $key => $value) {
                        $data = array(
                            'id'=>$value['parent_id'],
                            'item_status'=>1,
                        );
                        $this->model->query('pallets')->setItem( $data ); # UPDATE ITEM ON PALLET

                        $_items = array(
                            'id'=>$value['id'],
                            'item_status'=>2
                        );
                        $this->model->setItem( $_items );
                    }

                    $postData['hold_status'] = 2;
                    $this->model->update( $id, $postData ); # UPDATE HOLD DETAIL

                    $arr['message'] = 'ปล่อย Hold เรียบร้อย';
                    $arr['url'] = 'refresh';
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

    		echo json_encode($arr);
    	}
    	else{
    		$this->view->setData('item', $item);
            $this->view->setData('manage', $this->model->manage());
    		$this->view->setPage('path', 'Forms/hold');
    		$this->view->render('set');
    	}
    }

    #Cause
    public function add_cause(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/hold/cause');
        $this->view->render('add');
    }
    public function edit_cause($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getCause($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/hold/cause');
        $this->view->render('add');
    }
    public function save_cause(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getCause($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('cause_name')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $item['name'] == $postData['cause_name'] ) $has_name = false;
            }
            if( $this->model->is_cause($postData['cause_name']) && $has_name ){
                $arr['error']['cause_name'] = 'มีชื่อนี้ซ้ำอยู่ในระบบ';
            }

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->updateCause($id, $postData);
                }
                else{
                    $this->model->insertCause($postData);
                }
                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_cause($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getCause($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            $this->model->deleteCause($id);
            $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            $arr['url'] = 'refresh';

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/hold/cause');
            $this->view->render('del');
        }
    }

    #manage
    public function add_manage(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setPage('path', 'Forms/hold/manage');
        $this->view->render('add');
    }
    public function edit_manage($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getManage($id);
        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setPage('path', 'Forms/hold/manage');
        $this->view->render('add');
    }
    public function save_manage(){
        if( empty($_POST) ) $this->error();

        $id = isset($_POST["id"]) ? $_POST["id"] : null;
        if( !empty($id) ){
            $item = $this->model->getManage($id);
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('manage_name')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($item) ){
                if( $item['name'] == $postData['manage_name'] ) $has_name = false;
            }
            if( $this->model->is_manage($postData['manage_name']) && $has_name ){
                $arr['error']['manage_name'] = 'มีชื่อนี้ซ้ำอยู่ในระบบ';
            }

            if( empty($arr['error']) ){

                if( !empty($id) ){
                    $this->model->updateManage($id, $postData);
                }
                else{
                    $this->model->insertManage($postData);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }
        echo json_encode($arr);
    }
    public function del_manage($id=null){
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        if( empty($id) || empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->getManage($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->deleteManage($id);
            $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            $arr['url'] = 'refresh';

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/hold/manage');
            $this->view->render('del');
        }
    }

    #IMPORT
    public function import($type=null){
        $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : $type;
        if( empty($type) ) $this->error();

        if( !empty($_FILES) ){

            $target_file = $_FILES['file']['tmp_name'];

            require WWW_LIBS. 'PHPOffice/PHPExcel.php';
            require WWW_LIBS. 'PHPOffice/PHPExcel/IOFactory.php';

            $inputFileName = $target_file;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($inputFileName);

            $tap = isset($_REQUEST["tap"]) ? $_REQUEST["tap"] : 0;
            $objWorksheet = $objPHPExcel->setActiveSheetIndex($tap);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
            $headingsArray = $headingsArray[1];

            $r = -1;
            $data = array();
            $startRow = isset($_REQUEST['start_row']) ? $_REQUEST['start_row']:2;

            for ($row = $startRow; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);

                ++$r;
                $col = 0;
                foreach ($headingsArray as $columnKey => $columnHeading) {
                    $val = $dataRow[$row][$columnKey];

                    $text = '';
                    foreach (explode(' ', trim($val)) as $value) {
                        if( empty($value) ) continue;
                        $text .= !empty($text) ? ' ':'';
                        $text .= $value;
                    }

                    $data[$r][$col] = $text;
                    $col++;
                }

                $this->model->import( $type, $data[$r][1] );
            }

            $arr['message'] = 'บันทึกเรียบร้อย';
            $arr['url'] = 'refresh';

            echo json_encode($arr);
        }
        else{
            $this->view->setPage('path', 'Forms/hold/import');
            $this->view->render('add');
        }
    }
}
