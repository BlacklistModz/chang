<?php

$title = ' ชานชาลา';

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("name")
    	->label('ชื่อ ชานชาลา*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("address")
				->label('ที่อยู่ ชานชาลา')
				->autocomplete('off')
				->addClass('inputtext')
				->placeholder('')
				->type('textarea')
				->attr('data-plugins', 'autosize')
				->value( !empty($this->item['address'])? $this->item['address']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'platform/save"></form>';

# body
$arr['body'] = $form->html();

# title
if( !empty($this->item) ){
    $arr['title']= "แก้ไข{$title}";
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= "เพิ่ม{$title}";
}

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">'.$this->lang->translate('Save').'</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">'.$this->lang->translate('Cancel').'</span></a>';

// $arr['width'] = 782;

echo json_encode($arr);
