<?php 

$floor = array();
for ($i=1; $i <= 4 ; $i++) { 
	$floor[] = array('id'=>$i, 'name'=>'ชั้นที่ '.$i);
}

$title = '<i class="icon-plus"></i> เพิ่มการผลิต'.$this->types['name'];

if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
	$title = 'แก้ไขการผลิต'.$this->types['name'];
}
$arr['hiddenInput'][] = array('name'=>'pallet_type_id', 'value'=>$this->types['id']);

$form = new Form();
$form = $form->create()
    // set From
    ->elem('div')
    ->addClass('form-insert form-pallet');

$form 	->field('pallet_date')
		->label('CloseDate')
		->autocomplete('off')
		->addClass('inputtext')
		->type('date')
		->attr('data-plugins', 'datepicker')
		->value( !empty($this->item['date']) ? $this->item['date'] : '' );

$form 	->field('pallet_delivery_code')
		->label('เลขที่ใบส่งมอบ')
		->autocomplete('off')
		->addClass('inputtext')
		->value( !empty($this->item['delivery_code']) ? $this->item['delivery_code'] : '' );

$form 	->field('pallet_code')
		->label('PALLET CODE')
		->autocomplete('off')
		->addClass('inputtext')
		->value( !empty($this->item['code']) ? $this->item['code'] : '' );

$form 	->field('pallet_pro_id')
		->label('CODE *')
		->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->products['lists'], 'id', 'code' )
        ->value( !empty($this->item['pro_id']) ? $this->item['pro_id'] : '' );

$form 	->field('pallet_grade_id')
		->label('GRADE/สูตร')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->grade )
		->value( !empty($this->item['grade_id']) ? $this->item['grade_id'] : '' );

$form 	->field('pallet_size_id')
		->label('SIZE *')
		->attr( 'data-name', 'size' )
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->size )
		->value( !empty($this->item['size_id']) ? $this->item['size_id'] : '' );

$form 	->field('pallet_weight_id')
		->label('WEIGHT *')
		->attr( 'data-name', 'weight' )
		->autocomplete('off')
		->addClass('inputtext')
		->select( array() );

$form 	->field('pallet_pro_brand_id')
		->label('BRAND')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->brands )
		->value( !empty($this->item['pro_brand_id']) ? $this->item['pro_brand_id'] : '' );

$form 	->field('pallet_can_id')
		->label('CAN *')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->can )
		->value( !empty($this->item['can_id']) ? $this->item['can_id'] : '' );

$form 	->field('pallet_lid')
		->label('CAN LID *')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->lid )
		->value( !empty($this->item['lid']) ? $this->item['lid'] : '' );

$form 	->field('pallet_can_type_id')
		->label('CAN TYPE')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->canType )
		->value( !empty($this->item['can_type_id']) ? $this->item['can_type_id'] : '' );

$form 	->field('pallet_can_brand')
		->label('บริษัทผู้ผลิตกระป๋อง')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->canBrand )
		->value( !empty($this->item['can_brand']) ? $this->item['can_brand'] : '' );

$form 	->field('pallet_neck')
		->label('NECK/NON NECK *')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->neck )
		->value( !empty($this->item['neck']) ? $this->item['neck']['id'] : '' );

$form 	->field('pallet_brix_id')
		->label('ความหวาน')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->brix )
		->value( !empty($this->item['brix_id']) ? $this->item['brix_id'] : '' );

if( empty($this->item) ){
	$form 	->field('pallet_qty')
			->label('จำนวน (กระป๋อง) *')
			->autocomplete('off')
			->type('number')
			->addClass('inputtext')
			->value( !empty($this->item['qty']) ? $this->item['qty'] : '' );
}

$form 	->field('pallet_brand_id')
		->label('ยี่ห้อพาเลท')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->brand )
		->value( !empty($this->item['brand_id']) ? $this->item['brand_id'] : '' );

$form 	->field('pallet_ware_id')
		->label('โกดัง (ZONE)')
		->autocomplete('off')
		->addClass('inputtext')
		->attr('data-name', 'warehouse')
		->select( $this->warehouse )
		->value( !empty($this->item['ware_id']) ? $this->item['ware_id'] : '' );

$form 	->field('pallet_row_id')
		->label('แถวที่')
		->autocomplete('off')
		->addClass('inputtext')
		->attr('data-name', 'rows')
		->select( array() );

$form 	->field('pallet_deep')
		->label('ลึกที่')
		->autocomplete('off')
		->addClass('inputtext')
		->attr('data-name', 'deep')
		->select( array() );

$form 	->field('pallet_floor')
		->label('ชั้นที่')
		->autocomplete('off')
		->addClass('inputtext')
		->attr('data-name', 'floor')
		->select( $floor )
		->value( !empty($this->item['floor']) ? $this->item['floor'] : '' );

$form 	->field('pallet_note')
		->label('หมายเหตุ')
		->autocomplete('off')
		->addClass('inputtext')
		->attr('data-plugins', 'autosize')
		->type('textarea')
		->value( !empty($this->item['note']) ? $this->item['note'] : '' );

# set title
$arr['title'] = $title;

# set form
$arr['form'] = '<form class="js-submit-form" data-plugins="palletsForm" data-options="'.$this->fn->stringify( array(

		'size' => !empty($this->item['size_id']) ? $this->item['size_id'] : '',
		'grade' => !empty($this->item['grade_id']) ? $this->item['grade_id'] : '',
		'weight' => !empty($this->item['weight_id']) ? $this->item['weight_id'] : '',
		'rows' => !empty($this->item['row_id']) ? $this->item['row_id'] : '',
		'deep' => !empty($this->item['deep']) ? $this->item['deep'] : ''

	) ).'" method="post" action="'.URL. 'pallets/save"></form>';

# body
$arr['body'] = $form->html();

# button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

# settings
$arr['width'] = 750;
// $arr['height'] = 'full';
// $arr['overflowY'] = 'auto';

echo json_encode($arr);
?>