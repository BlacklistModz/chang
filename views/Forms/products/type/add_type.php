<?php

# title
$title = $this->lang->translate('Type');
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("type_code")
    	->label($this->lang->translate('Code').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['code'])? $this->item['code']:'' );

$form 	->field("type_name")
    	->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("type_icon")
			->label($this->lang->translate('Icon').'*')
				->autocomplete('off')
				->addClass('inputtext')
				->placeholder('')
			  ->value( !empty($this->item['icon'])? $this->item['icon']:'' );


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/save_type"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
