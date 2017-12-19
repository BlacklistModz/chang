<div class="customers-content<?=!empty($this->tabs_right) ? ' has-right':''?>">
	<div class="customers-main"><div class="mbl"><?php 

	foreach ($this->tabs as $key => $value) {

		$active = $value['id']==$this->tab ? ' active':'';
	?>
		
		<div data-content="<?=$value['id']?>" class="tab-content<?=$active?>"><?php

		require "sections/{$value['id']}.php";
		?></div>

	<?php } ?>
	</div></div>

	
	<?php if( !empty($this->tabs_right) ) { ?>
	<div class="customers-right">
		<a class="customers-right-link-toggle"><i class="icon-chevron-left"></i></a>
		<div class="customers-right-content">
	<?php

	foreach ($this->tabs_right as $key => $value) {

		$icon = !empty($value['icon']) ? '<i class="icon-'.$value['icon'].' mrs"></i>':'';
		$active = !empty($value['active']) ? ' active':'';
		$style = !empty($value['active']) ? '':' style="display:none"';
		echo '<section class="right-section'.$active.'" data-section="'.$value['id'].'">';
			echo '<header class="right-section-header" data-plugins="openParent">';
				echo '<span class="right-section-link-toggle"><i class="icon-plus"></i></span>';
				echo '<h3>'.$icon.$value['name'].'</h3>';
			echo '</header>';
			echo '<div class="right-section-content"'.$style.' rel="content">';
			require "right/{$value['id']}.php";
			echo '</div>';

		echo '</section>';
	}
		
	?></div></div>
	<!-- end: customers-right -->
	<?php } ?>

</div>
<!-- end: customers-content -->