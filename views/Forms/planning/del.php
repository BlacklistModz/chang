<?php

$arr['title'] = "ยืนยันการลบ";
if ( !empty($this->item['permit']['del']) ) {

	$arr['form'] = '<form class="js-submit-form" action="'.URL. 'planning/del"></form>';
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
	$arr['body'] = "คุณต้องการลบ <span class=\"fwb\">\"{$this->item['type_name']}\"</span> สัปดาห์ที่ <span class=\"fwb\">\"{$this->item['week']}\"</span> หรือไม่";

	$arr['button'] = '<button type="submit" class="btn btn-danger btn-submit"><span class="btn-text">ลบ</span></button>';
	$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';
}
else{

	$arr['body'] = "คุณไม่สามารถลบ <span class=\"fwb\">\"{$this->item['name']}\"</span>";
	$arr['button'] = '<a href="#" class="btn btn-cancel" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';
}


echo json_encode($arr);
