<?php 
$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert form-planload');

$form 	->field("plan_date")
		->label("DATE")
		->addClass("inputtext")
		->attr("data-plugins", "datepicker")
		->type("date")
		->value( !empty($this->item["date"]) ? $this->item["date"] : "" );

$form 	->field("plan_platform_id")
		->label("ซานชลา")
		->addClass("inputtext")
		->autocomplete("off")
		->select( $this->platform['lists'] )
		->value( !empty($this->item['platform_id']) ? $this->item['platform_id'] : "" );

$form 	->field("plan_cus_id")
		->label("BUYER")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->customer['lists'] )
		->value( !empty($this->item['cus_id']) ? $this->item['cus_id'] : "" );

$form 	->field("plan_type_id")
		->label("Product")
		->addClass('inputtext')
		->autocomplete('off')
		->select( $this->types )
		->value( !empty($this->item['type_id']) ? $this->item['type_id'] : "" );

$form 	->field("plan_pro_id")
		->label("Fruit")
		->addClass('inputtext')
		->autocomplete('off')
		->select( array() );

$form 	->field("plan_grade")
		->label("Grade")
		->text( '<div class="grade"></div>' );

$form 	->field("plan_size_id")
		->label("Size")
		->addClass("inputtext")
		->autocomplete('off')
		->attr("data-name", "size_id")
		->select( array() );

$form 	->field("plan_weight_id")
		->label("Weight")
		->addClass("inputtext")
		->autocomplete('off')
		->attr("data-name", "weight_id")
		->select( array() );

$form 	->field("plan_fcl")
		->label("FCL")
		->addClass("inputtext")
		->autocomplete('off')
		->value( !empty($this->item['fcl']) ? $this->item['fcl'] : '' );

$form 	->field("plan_carton")
		->label("Cartons")
		->addClass("inputtext")
		->autocomplete('off')
		->value( !empty($this->item['carton']) ? $this->item['carton'] : '' );

$form 	->field("plan_job_id")
		->label("JO NO.")
		->addClass("inputtext")
		->autocomplete('off')
		->select( $this->job['lists'], 'id', 'code' )
		->value( !empty($this->item['job_id']) ? $this->item['job_id'] : '' );

$form 	->field("plan_inv")
		->label("INV NO.")
		->addClass("inputtext")
		->autocomplete('off')
		->value( !empty($this->item['inv']) ? $this->item['inv'] : '' );

$form 	->field("plan_cabinet_return")
		->label("คืนตู้")
		->addClass("inputtext")
		->autocomplete('off')
		// ->attr("data-plugins", "datepicker")
		// ->type("date")
		->value( !empty($this->item['cabinet_return']) ? $this->item['cabinet_return'] : "" );

$form 	->field("plan_closed_date")
		->label("CLOSED")
		->addClass("inputtext")
		->autocomplete("off")
		// ->attr("data-plugins", "datepicker")
		// ->type("date")
		->value( !empty($this->item['closed_date']) ? $this->item['closed_date'] : "" );

$form 	->field("plan_cabinet_get")
		->label("รับตู้")
		->addClass("inputtext")
		->autocomplete("off")
		// ->attr("data-plugins", "datepicker")
		// ->type("date")
		->value( !empty($this->item['cabinet_get']) ? $this->item["cabinet_get"] : "" );

$form 	->field("plan_etd_date")
		->label("ETD")
		->addClass("inputtext")
		->autocomplete("off")
		// ->attr("data-plugins", "datepicker")
		// ->type("date")
		->value( !empty($this->item['etd_date']) ? $this->item["etd_date"] : "" );

$form 	->field("plan_ship")
		->label("SHIP")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item['ship']) ? $this->item['ship'] : '' );

$form 	->field("plan_shipper")
		->label("SHIPPER")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item['shipper']) ? $this->item['shipper'] : '' );

$form 	->field("plan_remark")
		->label("REMARK")
		->addClass("inputtext")
		->autocomplete("off")
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['remark']) ? $this->item['remark'] : '' );

$form 	->field("plan_approval")
		->label("ขออนุมัติส่งออก")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item['approval']) ? $this->item['approval'] : '' );

$form 	->field("plan_package_carton")
		->label("บรรจุภัณฑ์ (กล่อง)")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item['package_carton']) ? $this->item['package_carton'] : '' );

$form 	->field("plan_package_label")
		->label("บรรจุภัณฑ์ (ฉลาก)")
		->addClass("inputtext")
		->autocomplete("off")
		->value( !empty($this->item['package_label']) ? $this->item['package_label'] : '' );

$form 	->field("plan_carton_remark")
		->label("CARTON REMARK")
		->autocomplete("off")
		->addClass('inputtext')
		->type('textarea')
		->attr('data-plugins', 'autosize')
		->value( !empty($this->item['carton_remark']) ? $this->item['carton_remark'] : '' );
?>
<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<form class="js-submit-form" action="<?=URL?>planload/save" data-plugins="planloadForm">
				<h3 class="fwb mbm"><i class="icon-shopping-cart"></i> Planload en Create</h3>
				<div class="clearfix">
					<div class="uiBoxWhite pam pas" style="width: 950px;">
						<?=$form->html()?>
					</div>
					<div class="uiBoxWhite pam pas"  style="width: 950px;">
						<div class="clearfix">
							<a herf="<?=URL?>planload" class="btn btn-red">กลับ	</a>
							<button type="submit" class="js-submit btn btn-blue rfloat">บันทึก</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>