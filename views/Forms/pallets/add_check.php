<?php

# title
$title = 'ดึงตรวจ';
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}

$arr['hiddenInput'][] = array('name'=>'pallet_id', 'value'=>$this->pallet['id']);

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("check_qty")
    	->label('จำนวนที่นำไปตรวจ*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->type('number')
        ->value( !empty($this->item['qty'])? $this->item['qty']:'' );

$form 	->field("check_remark")
		->label("REMARK")
		->autocomplete('off')
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['remark']) ? $this->item['remark'] : '' );

$form 	->hr('<div><h4 style="color:red;">* การดึงตรวจจะเป็นการลบสินค้าใน PALLET จะไม่สามารถเรียกคืนมาได้อีก</h4></div>');

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'pallets/save_check"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
