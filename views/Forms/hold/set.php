<?php

$arr['title'] = 'ยืนยันการปล่อยโฮล';

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert pam pas');

$table = '<div class="lists-items" role="listsitems">
<table class="lists-items-listbox table-bordered">
    <thead>
    <tr style="background-color: beige;">                
        <th class="no">No.</th>
        <th class="name">จัดการ</th>
        <th class="number">ค่า</th>
        <th class="actions"></th>
    </tr>
    </thead>
    <tbody role="listsitem"></tbody>
</table>
</div>';

$form 	->field("manage")
		->label("วิธีจัดการ*")
		->text( $table );

$form 	->field("hold_manage_note")
		->label("หมายเหตุ")
		->autocomplete('off')
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['manage_note']) ? $this->item['manage_note'] : '' );

$form_options =  $this->fn->stringify( array(
		'manage'=>$this->manage,
        'items'=>!empty($this->item['manage']) ? $this->item['manage'] : array(),
    ) );

$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'hold/set_hold" data-plugins="holdManageForms" data-options="'.$form_options.'"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 650;
$arr['height'] = 'full';

echo json_encode($arr);