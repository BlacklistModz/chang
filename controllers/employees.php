<?php
class Employees extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){

        if( !empty($id) ){

            $item = $this->model->get($id);
            if( empty($item) ) $this->error();

            $this->view->setData('item', $item);
            $this->view->render("employees/profile/display");
        }
        else{

            if( $this->format=='json' ) {
                $this->view->setData('results', $this->model->query('employees')->lists( array('not_dep_id'=>5) ) );
                $render = "employees/lists/json";
            }
            else{

                // $this->view->elem('body')->addClass();
                // $this->view->setData('position', $this->model->query('employees')->position() );
                $render = "employees/lists/display";
            }

            $this->view->render($render);
        }

    }

    public function add() {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('dealer', $this->model->query('dealer')->lists() );
        $this->view->setData('prefixName', $this->model->query('system')->prefixName());
        $this->view->setData('city', $this->model->query('system')->city());
        $this->view->setData('department', $this->model->department() );
        $this->view->setData('position', $this->model->position() );

        $this->view->setPage('path','Forms/employees');
        $this->view->render("add");
    }

    public function edit($id=null) {
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);

        if( empty($item) ) $this->error();

        $this->view->setData('dealer', $this->model->query('dealer')->lists() );
        $this->view->setData('prefixName', $this->model->query('system')->prefixName());
        $this->view->setData('city', $this->model->query('system')->city() );
        $this->view->setData('department', $this->model->department() );
        $this->view->setData('position', $this->model->position() );

        $this->view->item = $item;
        $this->view->setPage('path','Forms/employees');
        $this->view->render("add");
    }

    public function save() {
        if( empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( !empty($id) ){
            $item = $this->model->get($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('emp_username')->val('is_empty')
                    ->post('emp_dep_id')->val('is_empty')
                    ->post('emp_pos_id')
                    ->post('emp_prefix_name')
                    ->post('emp_first_name')->val('is_empty')
                    ->post('emp_last_name')
                    ->post('emp_nickname')
                    ->post('emp_phone_number')
                    ->post('emp_email')
                    ->post('emp_line_id')
                    ->post('emp_address');

            $form->submit();
            $postData = $form->fetch();

            if( empty($item) ){
                $postData['emp_password'] = $_POST['emp_password'];
                if( empty($postData['emp_password']) ){
                    $arr['error']['emp_password'] = 'กรุณากรอกรหัสผ่าน';
                }else if( strlen($postData['emp_password']) < 6 ){
                    $arr['error']['emp_password'] = 'รหัสผ่านของคุณมีจำนวนต่ำกว่า 6 ตัวอักษร';
                }
            }

            $has_user = true;
            $has_name = true;
            if( !empty($id) ){
                if( $postData['emp_username'] == $item['username'] ){
                    $has_user = false;
                }

                if( $postData['emp_first_name'] == $item['first_name'] && $postData['emp_last_name'] == $item['last_name'] ){
                    $has_name = false;
                }
            }

            if( $this->model->is_user($postData['emp_username']) && $has_user == true ){
                $arr['error']['emp_username'] = "มี Username นี้อยู่ในระบบแล้ว";
            }

            if( $this->model->is_name( $postData['emp_first_name'] , $postData['emp_last_name'] ) && $has_name == true ){
                $arr['error']['emp_name'] = "มีชื่อ-นามสกุลนี้ ในระบบแล้ว";
            }


            $futureDate = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " -6 year"));
            $birthday = date("{$_POST['birthday']['year']}-{$_POST['birthday']['month']}-{$_POST['birthday']['date']}");
            if( strtotime($birthday) > strtotime($futureDate) ){
                $arr['error']['birthday'] = 'วันเกิดไม่ถูกต้อง';
            }


            // modify
            $postData['emp_username'] = trim($postData['emp_username']);
            $postData['emp_first_name'] = trim($postData['emp_first_name']);
            $postData['emp_last_name'] = trim($postData['emp_last_name']);
            $postData['emp_address'] = nl2br(trim($postData['emp_address']));
            
            /*$postData['emp_city_id'] = $postData['address']['city'];
            $postData['emp_zip'] = $_POST['address']['zip'];*/
            $postData['emp_birthday'] = $birthday;

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->update( $id, $postData );
                }
                else{
                    $this->model->insert( $postData );
                    $id = $postData['id'];
                }

                if( !empty($_FILES['file1']) ){

                    $userfile = $_FILES['file1'];

                    if( !empty($item['image_id']) ){
                        $this->model->query('media')->del($item['image_id']);
                        $this->model->update( $id, array('emp_image_id'=>0 ) );
                    }

                    $album = array('album_id'=>1);

                    if( empty($structure) ){
                        $structure = WWW_UPLOADS . $album['album_id'];
                        if( !is_dir( $structure ) ){
                            mkdir($structure, 0777, true);
                        }
                    }


                    /**/
                    /* get Data Album */
                    /**/
                    $options = array(
                        'album_obj_type' => isset( $_REQUEST['obj_type'] ) ? $_REQUEST['obj_type']: 'public',
                        'album_obj_id' => isset( $_REQUEST['obj_id'] ) ? $_REQUEST['obj_id']: 1,
                        );

                    if( isset( $_REQUEST['album_name'] ) ){
                        $options['album_name'] = $_REQUEST['album_name'];
                    }
                    $album = $this->model->query('media')->searchAlbum( $options );

                    if( empty($album) ){
                        $this->model->query('media')->setAlbum( $options );
                        $album = $options;
                    }

                    // set Media Data
                    $media = array(
                        'media_album_id' => $album['album_id'],
                        'media_type' => isset($_REQUEST['media_type']) ? $_REQUEST['media_type']: strtolower(substr(strrchr($userfile['name'],"."),1))
                        );

                    $options = array(
                        'folder' => $album['album_id'],
                        'has_quad' => true,
                        );

                    if( !isset($media['media_emp_id']) ){
                        $media['media_emp_id'] = $this->me['id'];
                    }

                    $this->model->query('media')->set( $userfile, $media, $options );

                    if( empty($media['error']) ){
                        $media = $this->model->query('media')->convert($media);
                    }
                    $item['image_id'] = $media['id'];
                    $this->model->update( $id, array('emp_image_id'=>$item['image_id'] ) );

                }

                // resize
                if( !empty($_POST['cropimage']) && !empty($item['image_id']) ){
                    $this->model->query('media')->resize($item['image_id'], $_POST['cropimage']);
                }

                $arr['message'] = 'บันทึกเรียบร้อย';
                // $arr['url'] = 'refresh';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());

            if( !empty($arr['error']['emp_first_name']) ){
                $arr['error']['name'] = $arr['error']['emp_first_name'];
            } else if( !empty($arr['error']['emp_last_name']) ){
                $arr['error']['name'] = $arr['error']['emp_last_name'];
            }
        }

        echo json_encode($arr);
    }

    // update Emp
    public function password($id='')  {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){
            try {
                $form = new Form();
                $form   ->post('password_new')->val('password')
                        ->post('password_confirm')->val('password');

                $form->submit();
                $dataPost = $form->fetch();

                if( $dataPost['password_new']!=$dataPost['password_confirm'] ){
                    $arr['error']['password_confirm'] = 'รหัสผ่านไม่ตรงกัน';
                }

                if( empty($arr['error']) ){

                    // update
                    $this->model->update($item['id'], array(
                        'emp_password' => Hash::create('sha256', $dataPost['password_new'], HASH_PASSWORD_KEY )
                    ));

                    $arr['message'] = "แก้ไขข้อมูลเรียบร้อย";
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item );

            $this->view->setPage('path','Forms/employees');
            $this->view->render("change_password");
        }
    }
    public function display($id=null, $status=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : $status;
        if( empty($this->me) || empty($id) || empty($status) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            $this->model->update($id, array('emp_display'=>$status));

            $arr['message'] = 'Saved';
            $arr['url'] = 'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            $this->view->status = $status;

            $this->view->setPage('path', "Forms/employees/");
            $this->view->render("change_display");
        }
    }

    public function del($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ($item['permit']['del']) {

                if( !empty($item['image_id']) ){
                    $this->model->query('media')->del($item['image_id']);
                }

                $this->model->delete($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            $arr['url'] = isset($_REQUEST['next']) ? $_REQUEST['next']:'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->item = $item;
            $this->view->setPage('path','Forms/employees');
            $this->view->render("del");
        }
    }

    public function del_image_profile($id=null)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: $id;
        if( empty($this->me) || $this->format!='json' || empty($id) ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            $this->model->query('media')->del($item['image_id']);
            $this->model->update( $id, array('emp_image_id'=>0 ) );

            $arr['message'] = "ลบเรียบร้อย";
            $arr['status'] = 1;
            echo json_encode($arr);
        }
        else{
            $this->view->id = $id;
            $this->view->setPage('path','Forms/employees');
            $this->view->render("del_image_profile");
        }
    }



    /**/
    /* department */
    /**/
    public function add_department(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('pageMenu', $this->model->query('system')->pageMenu());
        $this->view->setData('role', $this->model->query('system')->roles());

        $this->view->setPage('path','Forms/department');
        $this->view->render("add");
    }
    public function edit_department($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_department($id);
        if( empty($item) ) $this->error();
        // print_r($item); die;

        $this->view->setData('item', $item);
        $this->view->setData('role', $this->model->query('system')->roles());
        $this->view->setPage('path','Forms/department');
        $this->view->render("add");
    }
    public function save_department(){
        if( empty($this->me) || empty($_POST) || $this->format!='json' ) $this->error();

        if( isset($_REQUEST['id']) ){
            $id = $_REQUEST['id'];

            $item = $this->model->get_department($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('dep_name')->val('is_empty')
                    ->post('dep_notes');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($id) ){
                if( $postData['dep_name'] == $item['name'] ){
                    $has_name = false;
                }
            }

            if( $this->model->is_dep($postData['dep_name']) && $has_name == true ){
                $arr['error']['dep_name'] = "มีชื่อนี้อยู่ในระบบแล้ว";
            }

            if( empty($arr['error']) ){

                $postData['dep_access'] = !empty($_POST['access']) ? json_encode($_POST['access']) : '';

                if( !empty($item) ){
                    $this->model->update_department( $id, $postData );
                }
                else{
                    $this->model->insert_department( $postData );
                }

                $arr['url'] = 'refresh';
                $arr['message'] = 'บันทึกเรียบร้อย';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }

    public function del_department($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_department($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ( !empty($item['permit']['del']) ) {
                $this->model->delete_department($id);

                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : 'refresh';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/department');
            $this->view->render("del");
        }
    }
    // end: department


    /**/
    /* Position */
    /**/
    public function add_position(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('department', $this->model->department() );
        $this->view->setPage('path','Forms/position');
        $this->view->render("add");
    }
    public function edit_position($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_position($id);
        if( empty($item) ) $this->error();

        $this->view->setData('department', $this->model->department() );
        $this->view->setData('item', $item);
        $this->view->setPage('path','Forms/position');
        $this->view->render("add");
    }
    public function save_position(){
        if( empty($this->me) || empty($_POST) || $this->format!='json' ) $this->error();

        if( isset($_REQUEST['id']) ){
            $id = $_REQUEST['id'];

            $item = $this->model->get_position($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('pos_name')->val('is_empty')
                    ->post('pos_dep_id')
                    ->post('pos_notes');

            $form->submit();
            $postData = $form->fetch();

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->update_position( $id, $postData );
                }
                else{
                    $this->model->insert_position( $postData );
                }

                $arr['url'] = 'refresh';
                $arr['message'] = 'บันทึกเรียบร้อย';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }
    public function del_position($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_position($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ( !empty($item['permit']['del']) ) {
                $this->model->delete_position($id);

                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : 'refresh';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/position');
            $this->view->render("del");
        }
    }
    // end: position

    public function edit_basic($id=null){

        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->query('employees')->get($id);

        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setData('prefixName', $this->model->query('system')->_prefixName());
        $this->view->setData('department', $this->model->query('employees')->department());
        $this->view->setData('position', $this->model->query('employees')->position());
        $this->view->render('sales/forms/edit_basic_dialog');
    }

    public function edit_contact($id=null)
    {
        if( empty($this->me) || empty($id) ) $this->error();

        $item = $this->model->query('employees')->get($id);

        if( empty($item) ) $this->error();

        $this->view->setData('item', $item);
        $this->view->setData('city', $this->model->query('system')->city());
        $this->view->render('sales/forms/edit_contact_dialog');
    }

    public function update()
    {
        if( empty($this->me) || empty($_POST) ) $this->error();

        $id = isset($_POST['id']) ? $_POST['id']: null;
        if( empty($id) ) $this->eror();

        $item = $this->model->query('employees')->get($id);
        if( empty($item) ) $this->error();

        if( $_POST['section'] == 'basic' ){

            try{

                $form = new Form();
                $form   ->post('emp_prefix_name')->val('is_empty')
                        ->post('emp_first_name')->val('is_empty')
                        ->post('emp_last_name')->val('is_empty');

                $form->submit();
                $postData = $form->fetch();

                $futureDate = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " -6 year"));
                $birthday = date("{$_POST['birthday']['year']}-{$_POST['birthday']['month']}-{$_POST['birthday']['date']}");
                if( strtotime($birthday) > strtotime($futureDate) ){
                    $arr['error']['birthday'] = 'วันเกิดไม่ถูกต้อง';
                }

                $postData['emp_first_name'] = trim($postData['emp_first_name']);
                $postData['emp_last_name'] = trim($postData['emp_last_name']);
                $postData['emp_birthday'] = $birthday;

            }catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        elseif( $_POST['section'] == 'contact' ){

            try{

                $form = new Form();
                $form   ->post('emp_phone_number')
                        ->post('emp_email')
                        ->post('emp_line_id')
                        ->post('emp_notes');

                $form->submit();
                $postData = $form->fetch();

                foreach ($_POST['address'] as $key => $value) {
                if( empty($value) && $key != 'village' && $key !='street' && $key != 'alley') {
                    $arr['error']['emp_address'] = 'กรุณากรอกข้อมูลในช่องที่มีเครื่องหมาย * ให้ครบถ้วน';
                    }
                }

                if( !empty($_POST['address']['zip']) && strlen($_POST['address']['zip']) != 5 ){
                    $arr['error']['emp_address'] = 'กรุณากรอกรหัสไปรษณีย์ให้ครบ 5 หลัก';
                }

                $postData['emp_address'] = json_encode($_POST['address']);
                $postData['emp_city_id'] = $_POST['address']['city'];
                $postData['emp_zip'] = $_POST['address']['zip'];

            }catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }
        }
        else{
            $this->error();
        }

        if( empty($arr['error']) ){
            $this->model->query('employees')->update( $id, $postData );
            $arr['message'] = 'บันทึกข้อมูลเรียบร้อย';
            $arr['url'] = 'refresh';
        }

        echo json_encode($arr);
    }

    public function setdata($id='', $field=null)
    {
        if( empty($id) || empty($field) || empty($this->me) ) $this->error();

        $item = $this->model->get( $id );

        if( empty($item) ) $this->error();

        if( isset($_REQUEST['has_image_remove']) && !empty($item['image_id']) ){
            $this->model->query('media')->del( $item['image_id'] );
        }

        $data[$field] = isset($_REQUEST['value'])? $_REQUEST['value']:'';
        $this->model->update($id, $data);

        $arr['message'] = 'บันทึกเรียบร้อย';
        echo json_encode($arr);
    }

    /**/
    /* Note */
    /**/
    public function notes(){
        if( empty($this->me) || $this->format!='json') $this->error();
        echo json_encode($this->model->query('notes')->notes());
    }

    public function save_note(){
        if( empty($this->me) || empty($_POST) || $this->format!='json') $this->error();


        if( empty($_POST['text']) || empty($_POST['obj_id']) ){

            $arr['message'] = array('text'=>'กรุณากรอกข้อมูลให้ครบ!', 'bg'=>'red', 'auto'=>1, 'load'=>1) ;
            $arr['error'] = 1;
        }
        else{
            $data = array(
                'note_text' => $_POST['text'],
                'note_date' => date('Y-m-d H:s:i'),
                'note_emp_id' => $this->me['id'],
                'note_obj_id' => $_POST['obj_id'],
                'note_obj_type' => 'employees',
                );
            $this->model->query('notes')->save_note( $data );

            $arr['data'] = $data;

            $arr['message'] = 'บันทึกเรียบร้อย';
            $arr['url'] = 'refresh';

        }

        echo json_encode( $arr );
    }

    public function del_note($id=null) {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->query('notes')->get_note($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ($item['permit']['del']) {
                $this->model->query('notes')->deleteNote($id);
                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }
            $arr['url'] = 'refresh';
            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/notes');
            $this->view->render("del_note");
        }
    }

    public function edit_note(){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->query('notes')->get_note($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            try {
                $form = new Form();
                $form   ->post('note_text')->val('is_empty');

                $form->submit();
                $postData = $form->fetch();

                if( empty($arr['error']) ){

                    $this->model->query('notes')->updateNote($id, $postData );

                    $arr['message'] = 'บันทึกเรียบร้อย';

                    $arr['url'] = 'refresh';
                    $arr['text'] = $postData['note_text'];
                }

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path', 'Forms/notes');
            $this->view->render("get_note");
        }
    }

    public function edit_permit($id=null, $type=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : $type;

        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        if( $type == 'employees' ){
            $item = $this->model->get($id);
        }

        if( $type == 'department' ){
            $item = $this->model->get_department($id);
        }

        if( $type == 'position' ){
            $item = $this->model->get_position($id);
        }

        if( empty($item) ) $this->error();

        $this->view->setData('pageMenu', $this->model->query('system')->pageMenu());
        $this->view->setData('item', $item);

        $this->view->setPage('path','Forms/permission');
        $this->view->render("permission");
    }

    public function save_permit(){
        if( empty($this->me) || empty($_POST) || $this->format!='json' ) $this->error();

        if( isset($_REQUEST['id']) || isset($_REQUEST['type']) ){
            $id = $_REQUEST['id'];
            $type = $_REQUEST['type'];

            if( $type == 'employees' ){
                $item = $this->model->get($id);
            }

            if( $type == 'department' ){
                $item = $this->model->get_department($id);
            }

            if( $type == 'position' ){
                $item = $this->model->get_position($id);
            }

            if( empty($item) ) $this->error();
        }

        try {

            if( empty($arr['error']) ){

                if( $type == 'employees' ){
                    $postData['emp_permission'] = json_encode($_POST['permission']);
                    $this->model->update( $id , $postData );
                }

                if( $type == 'department' ){
                    $postData['dep_permission'] = json_encode($_POST['permission']);
                    $this->model->update_department( $id, $postData );
                }

                if( $type == 'position' ){
                    $postData['pos_permission'] = json_encode($_POST['permission']);
                    $this->model->update_position( $id , $postData );
                }

                $arr['url'] = 'refresh';
                $arr['message'] = 'กำหนดสิทธิ์เรียบร้อย';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }

    /**/
    /* skill */
    /**/
    public function add_skill(){
        if( empty($this->me) || $this->format!='json' ) $this->error();

        $this->view->setData('pageMenu', $this->model->query('system')->pageMenu());

        $this->view->setPage('path','Forms/skill');
        $this->view->render("add");
    }
    public function edit_skill($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_skill($id);
        if( empty($item) ) $this->error();
        // print_r($item); die;

        $this->view->setData('item', $item);
        $this->view->setPage('path','Forms/skill');
        $this->view->render("add");
    }
    public function save_skill(){
        if( empty($this->me) || empty($_POST) || $this->format!='json' ) $this->error();

        if( isset($_REQUEST['id']) ){
            $id = $_REQUEST['id'];

            $item = $this->model->get_skill($id);
            if( empty($item) ) $this->error();
        }

        try {
            $form = new Form();
            $form   ->post('skill_name')->val('is_empty');

            $form->submit();
            $postData = $form->fetch();

            $has_name = true;
            if( !empty($id) ){
                if( $postData['skill_name'] == $item['name'] ){
                    $has_name = false;
                }
            }

            if( $this->model->is_skill($postData['skill_name']) && $has_name == true ){
                $arr['error']['skill_name'] = "มีชื่อนี้อยู่ในระบบแล้ว";
            }

            if( empty($arr['error']) ){

                if( !empty($item) ){
                    $this->model->update_skill( $id, $postData );
                }
                else{
                    $this->model->insert_skill( $postData );
                }

                $arr['url'] = 'refresh';
                $arr['message'] = 'บันทึกเรียบร้อย';
            }

        } catch (Exception $e) {
            $arr['error'] = $this->_getError($e->getMessage());
        }

        echo json_encode($arr);
    }

    public function del_skill($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me) || empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get_skill($id);
        if( empty($item) ) $this->error();

        if (!empty($_POST)) {

            if ( !empty($item['permit']['del']) ) {
                $this->model->delete_skill($id);

                $arr['message'] = 'ลบข้อมูลเรียบร้อย';
                $arr['url'] = isset($_REQUEST['next'])? $_REQUEST['next'] : 'refresh';
            } else {
                $arr['message'] = 'ไม่สามารถลบข้อมูลได้';
            }

            if( isset($_REQUEST['callback']) ){
                $arr['callback'] = $_REQUEST['callback'];
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setPage('path','Forms/skill');
            $this->view->render("del");
        }
    }

    public function set_skill($id=null){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if( empty($this->me )|| empty($id) || $this->format!='json' ) $this->error();

        $item = $this->model->get($id);
        if( empty($item) ) $this->error();

        if( !empty($_POST) ){

            try {
                $this->model->unsetSkill( $id );

                if( !empty($_POST['skill']) ){

                    foreach ($_POST['skill'] as $key => $value) {

                        $skill = array(
                            'skill_id'=>$value,
                            'emp_id'=>$id,
                        );

                        $this->model->setSkill( $skill );
                    }
                }

                $arr['message'] = 'บันทึกเรียบร้อย';

            } catch (Exception $e) {
                $arr['error'] = $this->_getError($e->getMessage());
            }

            echo json_encode($arr);
        }
        else{
            $this->view->setData('item', $item);
            $this->view->setData('skill', $this->model->skill());
            $this->view->setPage('path', 'Forms/skill');
            $this->view->render("set");
        }
    }
    // end: skill
}
