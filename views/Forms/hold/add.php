<?php 

#SET DATE START & END
$startDate = '';
if( !empty($this->item['start_date']) ){
	$startDate = $this->item['start_date'];
}
elseif( isset($_REQUEST['date']) ){
	$startDate = $_REQUEST['date'];
}
$endDate = '';
if( !empty($this->item['end_date']) ){
	if( $this->item['end_date'] != '0000-00-00' ){
		$endDate = $this->item['end_date'];
	}
}

#SET FORM
$title = "Hold";
if( empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'hold_parent_id', 'value'=>$this->currPallet['id']);
	$arr['title'] = "เพิ่ม {$title}";
}
else{
	$arr['title'] = "แก้ไข {$title}";
	$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
	$arr['hiddenInput'][] = array('name'=>'hold_parent_id', 'value'=>$this->item['parent_id']);
}

$arr['hiddenInput'][] = array('name'=>'hold_type_id', 'value'=>$this->currPallet['type_id']);

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert pam pas');

$form 	->field("hold_pallet_id")
		->label("ย้ายสินค้าไปยังพาเลท*")
		->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->select( $this->pallets['lists'], 'id', 'name_str' )
        ->value( !empty($this->item['pallet_id']) ? $this->item['pallet_id'] : $this->currPallet['id'] );

$form 	->field("hold_qty")
		->label("จำนวนสินค้าที่ Hold")
		->autocomplete('off')
		->addClass('inputtext')
		->placeholder('')
		->type('number')
		->value( !empty($this->item['qty']) ? $this->item['qty'] : '' );

$table = '<div class="lists-items" role="listsitems">
<table class="lists-items-listbox table-bordered">
    <thead>
    <tr style="background-color: beige;">                
        <th class="no">No.</th>
        <th class="name">สาเหตุ</th>
        <th class="number">ค่า</th>
        <th class="actions"></th>
    </tr>
    </thead>
    <tbody role="listsitem"></tbody>
</table>
</div>';

$form 	->field("cause")
		->label("สาเหตุ*")
		->text( $table );

$form 	->field("hold_note")
		->label("หมายเหตุ")
		->autocomplete('off')
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['note']) ? $this->item['note'] : '' );

$options = $this->fn->stringify( array(
			'startDate' => $startDate,
			'endDate' => $endDate,

			'allday' => 'disabled',
			'endtime' => 'disabled',
			'time' => 'disabled',

			'str' => array(
				'เริ่ม',
				'ปล่อย',
				// $this->lang->translate('All day'),
				// $this->lang->translate('End Time'),
			),

			'lang' => $this->lang->getCode(),
			'name' => array('hold_start_date', 'hold_end_date'),
		) );

$form 	->field("period")
		->label("กำหนดการ Hold")
		->text( '<div data-plugins="setdate" data-options="'.$options.'"></div>' );

$form_options =  $this->fn->stringify( array(
		'cause'=>$this->cause,
        'items'=>!empty($this->item['cause']) ? $this->item['cause'] : array(),
    ) );
# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'hold/save" data-plugins="holdForms" data-options="'.$form_options.'"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn btn-red" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

$arr['width'] = 650;
$arr['height'] = 'full';

echo json_encode($arr);