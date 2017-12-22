<?php 

$arr['title'] = "คัดจำนวนของ".$this->status["name"];

$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'status', 'value'=>$this->status["id"]);

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("qty")
		->label("จำนวน")
		->addClass("inputtext")
		->autocomplete('off')
		->type('number')
		->value(0);

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'pallets/set_item"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);