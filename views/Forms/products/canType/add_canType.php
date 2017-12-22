<?php

# title
$title = $this->lang->translate('ชนิดกระป๋อง');
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("type_name")
    	->label('ชนิด*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/save_canType"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
