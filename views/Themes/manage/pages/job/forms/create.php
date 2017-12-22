<?php
$startDate = !empty($this->item['date']) ? $this->item['date'] : "";
if( !empty($this->item['date']) && $this->item['date'] == "0000-00-00" ){
	$startDate = "";
}

$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass('form-insert');

$form 	->field('job_date')
		->label("วันที่")
		->addClass('inputtext')
		->autocomplete('off')
		->attr('data-plugins', 'datepicker')
		->value( !empty($startDate) ? $this->item['date'] : '' );

$form 	->field('job_code')
		->label("JO CODE")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['code']) ? $this->item['code'] : '' );

$form 	->field('job_cus_id')
		->label("BUYER NAME")
		->addClass('inputtext')
		->autocomplete('off')
		->attr('data-name', 'customers')
		->select( $this->customers['lists'] )
		->value( !empty($this->item['cus_id']) ? $this->item['cus_id'] : '' );

/* $form 	->field("job_cus_address")
		->label("ADDRESS")
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->attr('data-name', 'address')
		->value( !empty($this->item['cus_address']) ? $this->item['cus_address'] : '' );

$form 	->field("job_cus_phone")
		->label("PHONE")
		->addClass('inputtext')
		->attr('data-name','phone')
		->value( !empty($this->item['cus_phone']) ? $this->item['cus_phone'] : '' );

$form 	->field("job_cus_fax")
		->label("FAX")
		->addClass('inputtext')
		->attr('data-name','fax')
		->value( !empty($this->item['cus_fax']) ? $this->item['cus_fax'] : '' ); */

$form2 = new Form();
$form2 = $form2->create()
			   ->elem('div')
			   ->addClass('form-insert');

$form2 	->field('job_payment')
		->label("PAYMENT TERM")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['payment']) ? $this->item['payment'] : '' );

$form2 	->field('job_currency')
		->label("CURRENCY")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->currency )
		->value( !empty($this->item['currency']) ? $this->item['currency'] : '' );

$form2 	->field('job_incoterm')
		->label("INCOTERM")
		->addClass('inputtext')
		->autocomplete('off')
		->value( !empty($this->item['incoterm']) ? $this->item['incoterm'] : '' );

$options = $this->fn->stringify( array(
		'items' => !empty($this->item['items']) ? $this->item['items'] : array(),
		'types' => $this->types,
		'brand' => $this->brands['lists'],
		'can' => $this->can,
		'lid' => $this->lid,
		'pack' => $this->pack
	) );
?>
<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<form class="js-submit-form" action="<?=URL?>job/save" data-plugins="joForm" data-options="<?=$options?>">
				<h3 class="fwb mbm"><i class="icon-shopping-cart"></i> Job Order en Create</h3>
				<div class="clearfix">
					<div class="uiBoxWhite pam pas">
						<?=$form->html()?>
					</div>
				</div>
				<!-- <div class="mtm">
					<div class="uiBoxWhite pam pas">
						<?=$form2->html()?>
					</div>
				</div> -->
				<div class="clearfix mtm">
					<div class="uiBoxWhite pam pas">
						<div class="lists-items" role="listsitems">
							<table class="lists-items-listbox table-bordered">
								<thead>
									<tr style="background-color: #3d3d8c; color:white;">
										<th width="5%" class="tac">No.</th>
										<th width="10%" class="tac">Products</th>
										<th width="10%" class="tac">Fruit</th>
										<th width="5%" class="tac">Size</th>
										<th width="10%" class="tac">Brands</th>
										<th width="5%" class="tac">Plained/Litbo</th>
										<th width="10%" class="tac">LID</th>
										<th width="5%" class="tac">Pack Size</th>
										<th width="10%" class="tac">Weight</th>
										<th width="10%" class="tac">QTY</th>
										<th width="15%" class="tac">Remark</th>
										<td width="5%"></td>
									</tr>
								</thead>
								<tbody role="listsitem"></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="uiBoxWhite mts pam clearfix">
					<div class="rfloat">
						<button type="submit" class="btn btn-blue btn-submit">บันทึก</button>
					</div>
					<div class="lfloat">
						<a href="<?=URL?>job" class="btn">ยกเลิก</a>
					</div>
				</div>
				<?php
				if( !empty($this->item) ){
					echo '<input type="hidden" name="id" value="'.$this->item['id'].'">';
				}
				?>
			</form>
		</div>
	</div>
</div>
