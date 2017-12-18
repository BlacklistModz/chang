<?php

$url = URL.'products/';

$path = !empty($_GET["type"]) ? "?type={$_GET["type"]}" : "";

?><div data-load="<?=URL?>settings/products/products" class="SettingCol offline">

<div class="SettingCol-header"><div class="SettingCol-contentInner">
	<div class="clearfix">
		<ul class="clearfix lfloat SettingCol-headerActions">

			<li><h2><span>สินค้า</span></h2></li>
			<li><a class="btn js-refresh"><i class="icon-refresh"></i></a></li>
			<li class="divider"></li>

			<li><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add<?=$path?>"><i class="icon-plus mrs"></i><span>เพิ่ม</span></a></li>

		</ul>
		<ul class="rfloat SettingCol-headerActions clearfix">
			<li id="more-link"></li>
		</ul>


	</div>

	<div class="mtm clearfix">
		<ul class="lfloat SettingCol-headerActions clearfix">
			<li><label>Type:</label>
				<select ref="selector" name="type" class="inputtext">
					<option value="">-</option>
					<?php
					foreach ($this->type as $key => $value) {
						$sel = '';
						if( !empty($_GET["type"]) ){
							if( $value['id'] == $_GET["type"] ) $sel = ' selected="1"';
						}
						echo '<option'.$sel.' value="'.$value['id'].'">'.$value['name'].'</option>';
					}
					?>
				</select>
			</li>
		</ul>
		<ul class="rfloat SettingCol-headerActions clearfix">
			<li>
				<label for="search-query">Search:</label>
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
			<th class="name" data-col="0">Type</th>
			<th class="icon_name" data-col="0">Count</th>
			<th class="actions" data-col="2">จัดการ</th>
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
			<div class="empty-title">การเชื่อมต่อผิดพลาด.</div>
		</div>

		<div class="empty-text">
			<div class="empty-icon"><i class="icon-list-ol"></i></div>
			<div class="empty-title">ไม่มีข้อมูล.</div>
		</div>
	</div>
	</div>
</div>

</div>
