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
    		$item = $this->model->get($id, array("items"=>true));
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
        $this->view->setPage('title', 'Create Job Order');

        $this->view->setData('types', $this->model->query('products')->type());
        $this->view->setData('customers', $this->model->query('customers')->lists( array('unlimit'=>true, 'sort'=>'name', 'dir'=>'ASC') ));
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

        $this->view->setPage('on','job');
        $this->view->setPage('title', 'Edit Job Order');

    	$item = $this->model->get($id, array('items'=>true));
    	if( empty($item) ) $this->error();

        $this->view->setData('types', $this->model->query('products')->type());
        $this->view->setData('customers', $this->model->query('customers')->lists( array('unlimit'=>true, 'sort'=>'name', 'dir'=>'ASC') ));
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

        $id = isset($_POST["id"]) ? $_POST["id"] : null;

        if( !empty($id) ){
            $item = $this->model->get($id, array('items'=>true));
            if( empty($item) ) $this->error();
        }

        try{
            $form = new Form();
            $form   ->post('job_code')
                    ->post('job_cus_id')->val('is_empty');
            $form->submit();
            $postData = $form->fetch();

            if( !empty($postData['job_code']) ){
                $has_code = true;
                if( !empty($item) ){
                    if( $item['code'] == $postData['job_code'] ) $has_code = false;
                }
                if( $this->model->is_code($postData['job_code']) && $has_code ){
                    $arr['error']['job_code'] = 'มี CODE นี้อยู่ในระบบแล้ว';
                }
            }

            $customer = $this->model->query('customers')->get($postData['job_cus_id']);
            $postData['job_cus_name'] = $customer['name'];
            $postData['job_cus_address'] = $customer['address'];
            $postData['job_cus_phone'] = $customer['contact_phone'];
            $postData['job_cus_fax'] = $customer['contact_fax'];
            $postData['job_currency'] = $customer['currency'];

            if( empty($arr['error']) ){
                if( !empty($id) ){
                    $this->model->update($id, $postData);
                }
                else{
                    $this->model->insert($postData);
                    $id = $postData['id'];
                }

                if( !empty($id) ){
                    if( !empty($item['items']) ){
                        $_items = array();
                        foreach ($item['items'] as $key => $value) {
                            $_items[$key] = $value['id'];
                        }
                    }

                    $c = count($_POST["items"]["type_id"]);
                    $items = $_POST["items"];
                    for($i=0;$i<=$c;$i++){
                        if( empty($items['type_id'][$i]) || 
                            empty($items['weight_id'][$i]) || 
                            empty($items['qty'][$i]) ) continue;

                        $_data = array(
                            'item_job_id'=>$id,
                            'item_type_id'=>$items['type_id'][$i],
                            'item_size_id'=>$items['size_id'][$i],
                            'item_pro_id'=>$items['pro_id'][$i],
                            'item_can_id'=>$items['can'][$i],
                            'item_lid'=>$items['lid'][$i],
                            'item_pack'=>$items['pack'][$i],
                            'item_weight_id'=>$items['weight_id'][$i],
                            'item_qty'=>$items['qty'][$i],
                            'item_remark'=>$items['remark'][$i]
                        );

                        if( !empty($_items[$i]) ){
                            $_data['id'] = $_items[$i];
                            unset($_items[$i]);
                        }
                        $this->model->setItem($_data);
                    }

                    if( !empty($_items) ){
                        foreach ($_items as $i_id) {
                            $this->model->delItem($i_id);
                        }
                    }
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                $arr['url'] = URL.'job';
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