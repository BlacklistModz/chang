<?php

# title
$title = 'PLANLOAD';
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("plan_cus_id")
		->label("BUYER")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->customer['lists'] )
		->value( !empty($this->item['cus_id']) ? $this->item['cus_id'] : "" );

$form 	->field("plan_type_id")
		->label("Product")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->types )
		->value( !empty($this->item['type_id']) ? $this->item['type_id'] : "" );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'planload/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
