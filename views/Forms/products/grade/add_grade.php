<?php

# title
$arr['title'] = 'GRADE/สูตร';
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("grade_type_id")
		->label("ผลไม้")
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->type )
		->value( !empty($this->item['type_id']) ? $this->item['type_id'] : $this->currType );

$form 	->field("grade_name")
    	->label('GRADE/สูตร')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/save_grade"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);