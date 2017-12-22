<?php

$url = URL.'warehouse/';

$path = !empty($_GET["ware"]) ? "?ware={$_GET["ware"]}" : "";

?><div data-load="<?=URL?>settings/warehouse/rows" class="SettingCol offline">

<div class="SettingCol-header"><div class="SettingCol-contentInner">
	<div class="clearfix">
		<ul class="clearfix lfloat SettingCol-headerActions">

			<li><h2><span>แถว</span></h2></li>
			<li><a class="btn js-refresh"><i class="icon-refresh"></i></a></li>
			<li class="divider"></li>

			<li><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_rows<?=$path?>"><i class="icon-plus mrs"></i><span>เพิ่ม</span></a></li>

		</ul>
		<ul class="rfloat SettingCol-headerActions clearfix">
			<li id="more-link"></li>
		</ul>


	</div>

	<div class="mtm clearfix">
		<ul class="lfloat SettingCol-headerActions clearfix">
			<li><label>Zone:</label>
				<select ref="selector" name="ware" class="inputtext">
					<?php
					foreach ($this->warehouse['lists'] as $key => $value) {
						$sel = '';
						if( !empty($_GET["ware"]) ){
							if( $value['id'] == $_GET["ware"] ) $sel = ' selected="1"';
						}
						echo '<option'.$sel.' value="'.$value['id'].'">'.$value['name'].'</option>';
					}
					?>
				</select>
			</li>
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
			<th class="name" data-col="0">แถวที่</th>
			<th class="status" data-col="1">จำนวนตั้ง(สูงสุด)</th>
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
