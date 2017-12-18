<?php

$url = URL .'hold/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_cause"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a></span>
	<!-- <span class=""><a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>import?type=cause"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Import New')?></span></a></span> -->

</div>

<div class="setting-title">สาเหตุของการ HOLD</div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name">สาเหตุ</th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name"><?=$item['name']?></td>

			<td class="actions whitespace">
				
				<span class=""><a data-plugins="dialog" href="<?=$url?>edit_cause/<?=$item['id'];?>" class="btn btn-orange"><i class="icon-pencil"></i></a></span>
				<span class=""><a data-plugins="dialog" href="<?=$url?>del_cause/<?=$item['id'];?>" class="btn btn-red"><i class="icon-trash"></i></a></span>
					
			</td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>