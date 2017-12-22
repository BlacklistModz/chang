<?php
$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$type[] = array('id'=>'cause', 'name'=>'สาเหตุ');
$type[] = array('id'=>'manage', 'name'=>'การจัดการ');

$form 	->field('type')
		->label('TYPE*')
		->addClass('inputtext')
		->select( $type )
		->value( !empty($_GET["type"]) ? $_GET["type"] : '' );

$form 	->field("file")
		->label("เลือกไฟล์")
		->addClass('inputtext')
		->type('file')
		->attr('accept', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel')
		->value('');

$form 	->field("start_row")
		->label("เริ่มอ่านข้อมูลในแถวที่")
		->addClass('inputtext')
		->type('number')
		->value(1);

$form 	->field("tap")
		->label("หน้าที่ต้องการ Import (แถบด้านล่าง แถบที่ 1 ใส่ 0)")
		->addClass('inputtext')
		->type('number')
		->value(0);

# set form
$arr['title'] = 'Import ไฟล์ xls, xlsx';

$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'hold/import" enctype="multipart/form-data"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
