<?php

# title
$title = $this->lang->translate('เพิ่มสินค้า');
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');


	$form 	->field("pro_code")
	->label('Code*')
	->autocomplete('off')
	->addClass('inputtext')
	->placeholder('')
	->value( !empty($this->item['code'])? $this->item['code']:'' );

	$form 	->field("pro_breed_id")
	->label('Breed code*')
	->autocomplete('off')
	->addClass('inputtext')
	->placeholder('')
	->select($this->breed['lists'])
	->value( !empty($this->item['breed_id'])? $this->item['breed_id']:'' );

	$form 	->field("pro_amount")
	->label('Count')
	->autocomplete('off')
	->addClass('inputtext')
	->placeholder('')
	->value( !empty($this->item["amount"]) ? $this->item["amount"] : "" );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
