<?php

$url = URL.'customers_brands/';

?><div data-load="<?=URL?>settings/accounts/customers_brands" class="SettingCol offline">

<div class="SettingCol-header"><div class="SettingCol-contentInner">
	<div class="clearfix">
		<ul class="clearfix lfloat SettingCol-headerActions">
			<li>
				<h2><i class="icon-user mrs"></i><span><?=$this->lang->translate('BAYER BRANDS')?></span></h2>
			</li>
			<li><a class="btn js-refresh"><i class="icon-refresh"></i></a></li>
			<li class="divider"></li>

			<li><a class="btn btn-blue" data-plugins="dialog" href="<?=URL?>brands/add"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></li>

		</ul>
		<ul class="rfloat SettingCol-headerActions clearfix">
			<li id="more-link"></li>
		</ul>

	</div>


	<div class="mtm clearfix">

<ul class="lfloat SettingCol-headerActions clearfix">

	<li><label>Status:</label> <select ref="selector" name="status" class="inputtext">
		<option value="">All</option>
		<?php foreach ($this->status as $key => $value) {
			$s = '';
			if( isset($_GET['status']) ){

				if( $_GET['status']==$value['id'] ){
					$s = ' selected="1"';
				}
			}
			?>
			<option<?=$s?> value="<?=$value['id']?>"><?=$value['name']?></option>
		<?php } ?>
	</select></li>

</ul>

		<ul class="rfloat SettingCol-headerActions clearfix">

			<li>
				<label for="search-query">ค้นหา:</label>
				<form class="form-search" action="#">
				<input class="search-input inputtext" type="text" id="search-query" placeholder="ค้นหา" name="q" autocomplete="off">
				<span class="search-icon">
			 		 <button type="submit" class="icon-search nav-search" tabindex="-1"></button>
				</span>

			</form></li>

		</ul>
	</div>
	<!-- <div class="setting-description mtm uiBoxYellow pam">Manage your personal employee settings.</div> -->
</div></div>

<div class="SettingCol-main">
	<div class="SettingCol-tableHeader"><div class="SettingCol-contentInner">
		<table class="settings-table admin"><thead><tr>
			<th class="name" data-col="0">Name</th>
			<th class="number" data-col="1">Status</th>
			<th class="actions" data-col="2">Action</th>
		</tr></thead></table>
	</div></div>
	<div class="SettingCol-contentInner">
	<div class="SettingCol-tableBody"></div>
	<div class="SettingCol-tableEmpty empty">
		<div class="empty-loader">
			<div class="empty-loader-icon loader-spin-wrap"><div class="loader-spin"></div></div>
			<div class="empty-loader-text">กำลังโหลด...</div>
		</div>
		<div class="empty-error">
			<div class="empty-icon"><i class="icon-link"></i></div>
			<div class="empty-title">การเชื่อมต่อล้มเหลว!</div>
		</div>

		<div class="empty-text">
			<div class="empty-icon"><i class="icon-user"></i></div>
			<div class="empty-title">ไม่พบผลลัพธ์.</div>
		</div>
	</div>
	</div>
</div>

</div>
