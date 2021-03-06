<?php

$title = ' รถขนส่ง';

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("name")
    		->label('ชื่อ คนขับ*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

$form 	->field("license_plate")
				->label('ป้ายทะเบียนรถ*')
				->autocomplete('off')
				->addClass('inputtext')
				->placeholder('')
				->value( !empty($this->item['license_plate'])? $this->item['license_plate']:'' );

$form 	->field("brand")
				->label('ยี่ห้อรถ*')
				->autocomplete('off')
				->addClass('inputtext')
				->placeholder('')
				->value( !empty($this->item['brand'])? $this->item['brand']:'' );

$form 	->field("detial")
				->label('รายละเอียดรถ')
				->autocomplete('off')
				->addClass('inputtext')
				->placeholder('')
				->type('textarea')
				->attr('data-plugins', 'autosize')
				->value( !empty($this->item['detial'])? $this->item['detial']:'' );


# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL.'trucks/save"></form>';

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
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

// $arr['width'] = 782;

echo json_encode($arr);
