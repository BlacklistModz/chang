<?php
$form = new Form();
$form = $form->create()
			 ->elem('div')
			 ->addClass('inputtext');
?>
<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<h3 class="fwb"><i class="icon-shopping-cart"></i> Job Order en Create</h3>
			<div class="uiBoxWhite pam pas">
				<?=$form->html()?>
			</div>
		</div>
	</div>
</div>