<?php 
$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert form-packing');

$form 	->field("pack_plan_id")
		->label("PLANLOAD")
		->addClass("inputtext")
		->autocomplete("off")
		->select( $this->planload['lists'], 'id', 'name' )
		->value( !empty($this->item['plan_id']) ? $this->item['plan_id'] : '' );	
?>
<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<form class="js-submit-form" action="<?=URL?>packing/save">
				<h3 class="fwb mbm"><i class="icon-cube"></i> PACKING</h3>
				<div class="uiBoxWhite pas pam">
					<?=$form->html()?>
				</div>
			</form>
		</div>
	</div>
</div>