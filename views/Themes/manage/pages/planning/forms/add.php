<?php 

$title = 'เพิ่มแผนการผลิต';
$icon = 'plus';

$n_week = date("W");

$form = new Form();
$form = $form->create()
	->url( URL."planning/save")
	->addClass('form-insert js-submit-form pal clearfix')
	->method('post');

$week = '';
for ($i=1; $i<=52; $i++) { 
	$sel = '';
	if( !empty($this->item) ){
		if( $this->item['week'] == $i ) $sel = ' selected="1"';
	}
	else{
		if( $n_week == $i ) $sel = ' selected="1"';
	}
	$date = $this->fn->q('time')->DayOfWeeks($i);
	$dateStr = $this->fn->q('time')->str_event_date($date['start'], $date['end']);
	$week .= '<option'.$sel.' value="'.$i.'">สัปดาห์ที่ : '.$i.' ('.$dateStr.')</option>';
}
$week = '<select class="inputtext" name="plan_week" style="width:20%">'.$week.'</select>';

$form 	->field("plan_week")
		->label("ประจำสัปดาห์ที่")
		->text( $week );

$form 	->field('plan_type_id')
		->label('ชนิดของผลิตภัฑณ์')
		->addClass('inputtext')
		->autocomplete('off')
		->attr('style', 'width: 20%;')
		->select( $this->type )
		->value( !empty($this->item['type_id']) ? $this->item['type_id'] : '' );

$form   ->hr('<div class="clearfix"></div>');

$table = '<div class="lists-items" role="listsitems">
<table class="lists-items-listbox table-bordered">
    <thead>
    <tr style="background-color: #72b413; color:white;">
        <th class="name js-th-grade">GRADE/สูตร</th>
        <th class="number hidden_elem js-select-products">CODE</th>
        <th class="number hidden_elem js-select-amount">จำนวนลูก/ชิ้น</th>
        <th class="number">SIZE</th>
        <th class="number">DW./NW.</th>
        <th class="number">LID</th>
        <th class="text">ชนิดกระป๋องที่ใช้บรรจุ</th>
        <th class="number">NECK/NON</th>
        <th class="number">จำนวน(ตู้)</th>
        <th class="date">ประมาณการส่งสินค้าเข้าคลัง</th>
        <th class="text">หมายเหตุ</th>
        <th class="actions"></th>
    </tr>
    </thead>
    <tbody role="listsitem"></tbody>
</table>

<table class="lists-items-summary"><tbody>
    <tr>
        <td class="colleft">
            <table></table>
        </td>
        <td class="colright">
            <table><tbody>
                <tr>
                	<td colspan="8"></td>
                    <td class="TOTAL" align="right">รวม จำนวน(ตู้):</td>
                    <td class="TOTAL" align="center"><span summary="qty">0</span></td>
                    <input type="hidden" name="plan_total_qty" value="">
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>

</div>';


$form 	->field('plan_item')
		->label('รายการ')
		->text( $table );

$form 	->field('plan_note')
		->label('หมายเหตุ')
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->attr('style', 'width: 100%;')
		->placeholder('')
		->autocomplete('off')
		->value( !empty($this->item['note']) ? $this->item['note']:'' );

$form 	->submit()
		->addClass("btn-submit btn btn-blue rfloat")
		->value("บันทึก");

if( !empty($this->item) ){
	$form 	->hr('<input type="hidden" name="id" value="'.$this->item['id'].'">');
	$title = 'แก้ไขแผนการผลิต';
	$icon = 'pencil';
}

$options =  $this->fn->stringify( array(
        'items'=> !empty($this->item) ? $this->item['items'] : array(),
        'can' => $this->can,
        'lid' => $this->lid,
        'canOptions' => $this->canOptions,
    ) );
?>
<div class="mtm pal">
	<!-- <img class="mrm" src="http://localhost/chang/public/images/logo/25x25.png"> -->
	<h2 class="fwb mbm"><i class="icon-<?=$icon?>"></i> <?=$title?></h2>
	<div class="uiBoxWhite" data-plugins="planningForm" data-options="<?=$options?>">
		<?=$form->html()?>
	</div>
</div>