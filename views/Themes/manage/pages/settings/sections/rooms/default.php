<?php

$url = URL .'rooms/';


?><div class="pal">

<div class="setting-header clearfix">

	<div class="rfloat">

		<!-- <span class="gbtn"><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span> -->

	</div>

	<div class="setting-title"><?=$this->lang->translate('Rooms')?></div>

	<nav class="setting-header-taps"><?php
	
    ?>
</div>

<section class="setting-section">

	<ul class="ui-list ui-list-rooms" data-plugins="setrooms">
		<li class="active">
			<div class="inner">
				<div class="box">
					<label class="fwb fcg fss">Dealer: </label><select class="inputtext" name="dealer"><?php
						foreach ($this->dealer['lists'] as $key => $value) {
						    	
							$active = $this->dealer_id == $value['id'] ? ' selected':'';
							echo '<option'.$active.' value="'.$value['id'].'">'.$value['name'].'</option>';
						}
					?></select>
					<!-- <div class="actions"><a class="icon-pencil"></a><a class="icon-plus"></a><a class="icon-remove"></a></div> -->
				</div>
			</div>
			<ul class="floors"></ul>
		</li>
	</ul>

</section>
</div>