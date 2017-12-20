<?php
// print_r($this->data);die;

$url = URL .'pallets/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_retort"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>

</div>

<div class="setting-title">Retort</div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="status"><?=$this->lang->translate('ID')?></th>
			<th class="name"><?=$this->lang->translate('Name')?></th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name"><?=$item['id']?></td>
			<td class="status" style="width: 90px;"><?=$item["name"]?></td>

			<td class="actions whitespace">

				<span class=""><a data-plugins="dialog" href="<?=$url?>edit_retort/<?=$item['id'];?>" class="btn btn-orange"><i class="icon-pencil"></i></a></span>
				<span class=""><a data-plugins="dialog" href="<?=$url?>del_retort/<?=$item['id'];?>" class="btn btn-red"><i class="icon-trash"></i></a></span>

			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>
