<?php
class Packing extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($id=null){

    }
    public function add(){

    }
    public function edit($id=null){

    }
    public function save(){
    	if( empty($_POST) ) $this->error();
    }
    public function del($id=null){
    	
    }
}
