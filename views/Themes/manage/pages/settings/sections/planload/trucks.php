<?php

$url = URL .'trucks/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>
	<!-- <span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>import?type=manage"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Import New')?></span></a></span> -->

</div>

<div class="setting-title">รถขนส่ง</div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name">ชื่อคนขับ</th>
			<th class="name">ป้ายทะเบียนรถ</th>
			<th class="name">ยี่ห้อรถ</th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data['lists'] as $key => $item) { ?>
		<tr>
			<td class="name"><?=$item['name']?></td>
			<td class="name"><?=$item['license_plate']?></td>
			<td class="name"><?=$item['brand']?></td>

			<td class="actions whitespace">

				<span class="gbtn"><a data-plugins="dialog" href="<?=$url?>edit/<?=$item['id'];?>" class="btn btn-orange btn-no-padding"><i class="icon-pencil"></i></a></span>
				<span class="gbtn"><a data-plugins="dialog" href="<?=$url?>del/<?=$item['id'];?>" class="btn btn-red btn-no-padding"><i class="icon-trash"></i></a></span>

			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>
