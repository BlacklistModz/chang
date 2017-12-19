<?php

class Settings extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index( ){
    	$this->my();
    }

    public function company( $tap='basic' ) {
      $this->view->setPage("title", "Setting ".ucfirst($tap));

      $this->view->setPage('on', 'settings' );
      $this->view->setData('section', 'company');
      $this->view->setData('tap', 'display');
      $this->view->setData('_tap', $tap);

      if( empty($this->permit['company']['view']) ) $this->error();
      // print_r($this->permit); die;

      if( $tap != 'basic' ){

        $this->error();
      }

      if( !empty($_POST) && $this->format=='json' ){

        foreach ($_POST as $key => $value) {
          $this->model->query('system')->set( $key, $value);
        }

        $arr['url'] = 'refresh';
        $arr['message'] = 'บันทึกเรียบร้อย';

        echo json_encode($arr);
      }
      else{
        $this->view->render("settings/display");
      }

    }

    public function my( $tap='basic' ) {

        $this->view->setPage("title", "Setting ".ucfirst($tap));

        $this->view->setPage('on', 'settings' );
        $this->view->setData('section', 'my');
        $this->view->setData('tap', 'display');
        $this->view->setData('_tap', $tap);

        if( $tap=='basic' ){
            $this->view
            ->js(  VIEW .'Themes/'.$this->view->getPage('theme').'/assets/js/bootstrap-colorpicker.min.js', true)
            ->css( VIEW .'Themes/'.$this->view->getPage('theme').'/assets/css/bootstrap-colorpicker.min.css', true);

            $this->view->setData('prefixName', $this->model->query('system')->prefixName());
        }

        $this->view->render("settings/display");
    }

    /**/
    /* Manage employees */
    /**/
    public function accounts($tap='employees'){

      $this->view->setPage("title", "Setting employees - ".ucfirst($tap));

      $this->view->setPage('on', 'settings' );
      $this->view->setData('section', 'accounts');
      $this->view->setData('tap', $tap);
      $render = 'settings/display';

      if($tap=='position'){
        $data = $this->model->query('employees')->position();
      }
      elseif($tap=='customers'){
        if( $this->format=='json' ){
          $this->view->setData('results', $this->model->query('customers')->lists());
          $render = 'settings/sections/accounts/customers/lists/json';
        }
        $data = array();
      }
      elseif($tap=='department'){
        $this->view->setData('access', $this->model->query('system')->roles());
        $data = $this->model->query('employees')->department();
      }
      elseif($tap=='employees'){

        if( $this->format=='json' ){
          // sleep(5);
          $this->view->setData('results', $this->model->query('employees')->lists());

          $render = 'settings/sections/accounts/employees/lists/json';
        }

        $this->view->setData('department', $this->model->query('employees')->department() );
        $this->view->setData('position', $this->model->query('employees')->position() );
        $this->view->setData('display', $this->model->query('employees')->display() );
        $data = array();
      }
      else{
        $this->error();
      }

      $this->view->setData('data', $data);
      $this->view->render( $render );
    }

    /**/
    /* Property */
    /**/
    public function products($tap='type') {
      $this->view->setPage("title", "Setting Products - ".ucfirst($tap));

      $this->view->setPage('on', 'settings');
      $this->view->setData('section', 'products');
      $this->view->setData('tap', $tap);
      $render = 'settings/display';

      if( $tap=='type' ){
        $this->view->setData('size', $this->model->query('products')->size());
        $data = $this->model->query('products')->type();
      }
      elseif( $tap=='size' ){
        $data = $this->model->query('products')->size();
      }
      elseif( $tap=='brand' ){
        $data = $this->model->query('products')->brand();
      }
      elseif( $tap=='can' ){
        $data = $this->model->query('products')->can();
      }
      elseif( $tap=='canType' ){
        $data = $this->model->query('products')->canType();
      }
      elseif( $tap=='breed' ){
        $data = array();

        if( $this->format=='json' ){
          $results = $this->model->query('products')->breed();
          $this->view->setData('results', $results);
          $render = 'settings/sections/products/breed/json';
        }
        else{
          $this->view->setData('type', $this->model->query('products')->type());
        }
      }
      elseif( $tap=='old' ){
        $data = array();

        if( $this->format=='json' ){
          $results = $this->model->query('products')->old();
          $this->view->setData('results', $results);
          $render = 'settings/sections/products/old/json';
        }
        else{
          $this->view->setData('type', $this->model->query('products')->type());
        }
      }
      elseif( $tap=='grade' ){
        $data = array();

        if( $this->format=='json' ){
          $results = $this->model->query('products')->grade();
          $this->view->setData('results', $results);
          $render = 'settings/sections/products/grade/json';
        }
        else{
          $this->view->setData('type', $this->model->query('products')->type());
        }
      }
      elseif( $tap=='weight' ){
        $data = $this->model->query('products')->weight();
      }
      elseif( $tap=='products' ){
        $data = array();

        if( $this->format=='json' ){
          $results = $this->model->query('products')->lists();
          $this->view->setData('results', $results);
          $render = 'settings/sections/products/products/json';
        }
        else{
          $this->view->setData('type', $this->model->query('products')->type());
        }
      }
      else{
        $this->error();
      }

      $this->view->setData('data', $data);
      $this->view->render( $render );

    }

    public function pallets($tap='brands'){
      $this->view->setPage("title", "Setting Pallet - ".ucfirst($tap));

      $this->view->setPage('on', 'settings');
      $this->view->setData('section', 'pallets');
      $this->view->setData('tap', $tap);
      $render = 'settings/display';

      if( $tap=='brands' ){
        $data = $this->model->query('pallets')->brand();
      }
      else{
        $this->error();
      }

      $this->view->setData('data', $data);
      $this->view->render( $render );
    }

    public function warehouse($tap='lists'){
      $this->view->setPage("title", "Setting WareHouse - ".ucfirst($tap));

      $this->view->setPage('on', 'settings');
      $this->view->setData('section', 'warehouse');
      $this->view->setData('tap', $tap);
      $render = 'settings/display';

      if( $tap=='lists' ){
        $data = $this->model->query('warehouse')->lists( array('sort'=>'name', 'dir'=>'ASC') );
      }
      elseif( $tap=='rows' ){
        $data = array();
        if( $this->format=='json' ){
          $this->view->setData('results', $this->model->query('warehouse')->listsRows( array('dir'=>'ASC') ));
          $render = 'settings/sections/warehouse/rows/json';
        }
        $this->view->setData('warehouse', $this->model->query('warehouse')->lists( array('sort'=>'name', 'dir'=>'ASC') ));
      }
      else{
        $this->error();
      }

      $this->view->setData('data', $data);
      $this->view->render( $render );
    }

    public function hold($tap='cause'){
      $this->view->setPage('title', "Setting Hold - ".ucfirst($tap));

      $this->view->setPage('on', 'settings');
      $this->view->setData('section', 'hold');
      $this->view->setData('tap', $tap);
      $render = 'settings/display';

      if( $tap=='cause' ){
        $data = $this->model->query('hold')->cause();
      }
      elseif( $tap=='manage' ){
        $data = $this->model->query('hold')->manage();
      }
      else{
        $this->error();
      }

      $this->view->setData('data', $data);
      $this->view->render( $render );
    }

    // กลุ่มสร้างกลุ่มใหม่สำหรับ customers
    public function group() {
      $a[] = array( "id"=>"A", "name"=>"A", "A"=>"UP TO USD 200,000");
      $a[] = array( "id"=>"B", "name"=>"B", "B"=>"USD 100,000-200,000");
      $a[] = array( "id"=>"C", "name"=>"C", "C"=>"LOWER USD 100,000");
      return $a;
    }
}
