<?php

# title
$title = 'แถว';
if( !empty($this->item) ){
    $arr['title']= 'แก้ไข '.$title;
    $arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}
else{
    $arr['title']= 'เพิ่ม '.$title;
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("row_ware_id")
		->label("เลือกโกดัง (Zone)*")
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->warehouse['lists'] )
		->value( !empty($this->item['ware_id']) ? $this->item['ware_id'] : $this->currWare );

$form 	->field("row_name")
    	->label('แถว')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("row_deep")
    	->label('จำนวนลึก(ตั้ง) สูงสุด')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->maxlength(2)
        ->placeholder('')
        ->value( !empty($this->item['deep'])? $this->item['deep']:'' );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'warehouse/save_rows"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
