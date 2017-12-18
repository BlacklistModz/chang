<?php

$url = URL .'products/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_weight"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>

</div>

<div class="setting-title">Weight</div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name"><?=$this->lang->translate('Weight')?></th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name"><span class="fwb">DW : </span><?=$item['dw']?> , <span class="fwb">NW : </span><?=$item['nw']?></td>

			<td class="actions whitespace">
				
				<span class="gbtn">
					<a data-plugins="dialog" href="<?=$url?>edit_weight/<?=$item['id'];?>" class="btn btn-no-padding btn-orange"><i class="icon-pencil"></i></a>
				</span>
				<span class="gbtn">
					<a data-plugins="dialog" href="<?=$url?>del_weight/<?=$item['id'];?>" class="btn btn-no-padding btn-red"><i class="icon-trash"></i></a>
				</span>
					
			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>