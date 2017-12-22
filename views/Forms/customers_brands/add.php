<?php

# title
$title = $this->lang->translate('Brands');
if( !empty($this->item) ){
    $arr['title']= $title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= $title;
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

// ประเภท
$form 	->field("name")
        ->label($this->lang->translate('Name').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("status")
        ->label($this->lang->translate('Status').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->status )
        ->value( isset($this->item['status'])? $this->item['status']['id']:'' );

$role = '';

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'brands/save"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
