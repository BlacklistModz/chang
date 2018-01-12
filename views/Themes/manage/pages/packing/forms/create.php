<?php
$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert form-packing');



$options = $this->fn->stringify( array(
		'items' => !empty($this->item['items']) ? $this->item['items'] : array(),
) );
?>



<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<form class="js-submit-form" action="<?=URL?>packing/save" data-plugins="packingForm" data-options="<?=$options?>">
				<h3 class="fwb mbm"><i class="icon-cube"></i> PACKING</h3>
				<div class="uiBoxWhite pas pam" style="width:900px;">
					<?=$form->html()?>
					<div class="lists-items" role="listsitems">
						<table class="lists-items-listbox table-bordered">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="45%">PLANLOAD/JO NO.</th>
									<th width="12%" style="text-align: center;">จำนวน</th>
									<th width="12%" style="text-align: center;">บุบ</th>
									<th width="12%" style="text-align: center;">เสียหาย</th>
									<th width="12%" style="text-align: center;">จัดการ</th>
								</tr>
							</thead>
							<tbody role="listsitem"></tbody>
						</table>
					</div>
				</div>
				<div class="uiBoxWhite pas pam clearfix"  style="width:900px;">
					<a href="" class="btn btn-red lfloat">กลับ</a>
					<button type="submit" class="btn btn-blue btn-submit rfloat">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>
