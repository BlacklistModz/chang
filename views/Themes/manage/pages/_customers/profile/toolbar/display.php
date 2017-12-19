<?php

?>
<div class="profile-toolbar profile-toolbar-mg">
		
	<nav class="profile-actions-toolbar clearfix tab-action"><?php

		foreach ($this->tabs as $key => $value) {

			$icon = !empty($value['icon']) ? '<i class="icon-'.$value['icon'].' mrs"></i>':'';
			$active = $value['id']==$this->tab ? ' class="active" ':'';

			$count = !empty($value['count']) ? $value['count']:0;

			echo '<a'.$active.' data-action="'.$value['id'].'">'.$icon.'<span>'.$value['name'].'</span><span class="countVal">'.$count.'</span></a>';
		}

	?>
	</nav>
</div>