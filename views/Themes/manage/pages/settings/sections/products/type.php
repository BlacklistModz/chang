<?php

$url = URL .'products/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_type"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>

</div>

<div class="setting-title"><?=$this->lang->translate('Products Types')?></div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<!-- <th class="ID"></th> -->
			<th class="number"><?=$this->lang->translate('Code')?></th>
			<th class="name"><?=$this->lang->translate('Type')?></th>
			<?php
			foreach ($this->size as $key => $value) {
				echo '<th class="status">'.$value['name'].'</th>';
			}
			?>
			<th class="actions"><?=$this->lang->translate('Action')?></th>
		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<!-- <td class="ID"><i class="icon-<?=$item['icon']?> _ico-center"></i></td> -->
			<td class="number tac"><span class="fwb"><?=$item['code']?></span></td>
			<td class="name"><?=$item['name']?></td>
			<?php foreach ($this->size as $key => $value) { ?>
				<td class="status">
					<span class="gbtn">
						<a data-plugins="dialog" href="<?=$url?>setWeight/<?=$item['id']?>/<?=$value['id']?>" class="btn btn-blue btn-no-padding"><i class="icon-wrench"></i></a>
					</span>
				</td>
			<?php } ?>
			<td class="actions whitespace">

				<span class="gbtn"><a data-plugins="dialog" href="<?=$url?>edit_type/<?=$item['id'];?>" class="btn btn-orange btn-no-padding"><i class="icon-pencil"></i></a></span>
				<span class="gbtn"><a data-plugins="dialog" href="<?=$url?>del_type/<?=$item['id'];?>" class="btn btn-red btn-no-padding"><i class="icon-trash"></i></a></span>

			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>
