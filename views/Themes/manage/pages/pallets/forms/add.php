<?php
$floor = array();
for ($i=1; $i <= 4 ; $i++) {
	$floor[] = array('id'=>$i, 'name'=>'ชั้นที่ '.$i);
}

$title = '<i class="icon-plus"></i> เพิ่มการผลิต'.$this->types['name'];

if( !empty($this->item) ){
	$title = '<i class="icon-pencil"></i> แก้ไขการผลิต'.$this->types['name'];
}

$options = $this->fn->stringify( array(
		'type' => !empty($this->item['type_id']) ? $this->item['type_id'] : $this->types['id'],
		'size' => !empty($this->item['size_id']) ? $this->item['size_id'] : '',
		'grade' => !empty($this->item['grade_id']) ? $this->item['grade_id'] : '',
		'weight' => !empty($this->item['weight_id']) ? $this->item['weight_id'] : '',
		'rows' => !empty($this->item['row_id']) ? $this->item['row_id'] : '',
		'deep' => !empty($this->item['deep']) ? $this->item['deep'] : '',
		'items'=> !empty($this->item['retort']) ? $this->item['retort'] : array(),
		'retort' => $this->retort,
		'batch' => $this->batch
	) );

$form = new Form();
$form = $form->create()
    // set From
    // ->url(URL.'pallets/save')
	->elem('div')
	->addClass('form-insert form-pallet');

$form 	->field('pallet_date')
		->label('CloseDate')
		->autocomplete('off')
		->addClass('inputtext')
		->type('date')
		->attr('data-plugins', 'datepicker')
		->value( !empty($this->item['date']) ? $this->item['date'] : '' );

$form 	->field('pallet_code')
		->label('PALLET NUMBER')
		->autocomplete('off')
		->addClass('inputtext')
		->value( !empty($this->item['code']) ? $this->item['code'] : '' );

$form 	->field('pallet_delivery_code')
		->label('เลขที่ใบส่งมอบ')
		->autocomplete('off')
		->addClass('inputtext')
		->value( !empty($this->item['delivery_code']) ? $this->item['delivery_code'] : '' );

$form 	->field('pallet_pro_id')
		->label('CODE *')
		->autocomplete('off')
        ->addClass('inputtext')
        ->select( $this->products['lists'], 'id', 'code' )
        ->value( !empty($this->item['pro_id']) ? $this->item['pro_id'] : '' );

$form 	->field('pallet_old_id')
		->label('ความอ่อน/แก่')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->old['lists'], 'id', 'code' )
		->value( !empty($this->item['old_id']) ? $this->item['old_id'] : '' );

$form 	->field('pallet_grade_id')
		->label('GRADE/สูตร')
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->grade['lists'] )
		->value( !empty($this->item['grade_id']) ? $this->item['grade_id'] : '' );

$form 	->hr('<div class="clearfix"></div>');

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

$form 	->field('pallet_breed_id')
		->label("พันธุ์")
		->autocomplete('off')
		->addClass('inputtext')
		->select( $this->breed['lists'] )
		->value( !empty($this->item['breed_id']) ? $this->item['breed_id'] : "" );

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

$form 	->hr('<div class="clearfix"></div>');

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

// if( empty($this->item) ){
// 	$form 	->field('pallet_qty')
// 			->label('จำนวน (กระป๋อง) *')
// 			->autocomplete('off')
// 			->type('number')
// 			->addClass('inputtext')
// 			->value( !empty($this->item['qty']) ? $this->item['qty'] : '' );
// }

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

$types = !empty($this->item['type_id']) ? $this->item['type_id'] : $this->types['id'];
$form 	->hr('<input type="hidden" name="pallet_type_id" class="hiddenInput" value="'.$types.'">');
$form 	->hr('<input type="hidden" name="url" class="hiddenInput" value="'.URL.'pallets/'.$this->types['id'].'">');

if( !empty($this->item) ){
	$form 	->hr('<input type="hidden" name="id" class="hiddenInput" value="'.$this->item['id'].'">');
}


$form2 = new Form();
$form2 = $form2 ->create()
		->elem('div')
		->addClass('form-insert form-pallet');

$form2 	->field("rt_lists")
		->label("RT")
		->text( '<div role="tableRT">
					<table class="table-bordered" width="100%">
						<thead>
							<tr>
								<th class="ID" width="17.5%">RT</th>
								<th class="status" width="17.5%">BT</th>
								<th class="status" width="20%">QTY</th>
								<th class="status" width="17%">Hr.</th>
								<th class="status" width="17%">Min.</th>
								<th class="actions" width="10%"></th>
							</tr>
						</thead>
						<tbody role="listsRT"></tbody>
					</table>
					<div class="tac">
						<span class="gbtn">
							<a class="btn btn-blue btn-no-padding js-add"><i class="icon-plus"></i></a>
						</span>
					</div>
				</div>'
		);
?>
<div id="mainContainer" class="Setting clearfix" data-plugins="main">
	<div role="main">
		<div class="clearfix">
			<h2 class="pal fwb"><?=$title?></h2>
		</div>
		<div class="clearfix">
			<form class="js-submit-form" data-plugins="palletsForm" data-options="<?=$options?>" method="POST" action="<?=URL?>pallets/save">
				<div class="pll mbl span6" style="width: 700px; margin-left: -2mm">
					<div class="uiBoxWhite pam">
						<?=$form->html()?>
					</div>
				</div>
				<div class="span4" style="margin-left: 2mm">
					<div class="uiBoxWhite pam">
						<?=$form2->html(); ?>
					</div>
					<div class="clearfix uiBoxWhite pam mtl">
						<div class="lfloat">
							<a href="<?=URL?>pallets/<?=$this->types['id']?>" class="btn btn-red">ยกเลิก</a>
						</div>
						<div class="rfloat">
							<button type="submit" class="btn btn-primary btn-submit">
								<span class="btn-text">บันทึก</span>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
