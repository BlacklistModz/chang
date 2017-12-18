<?php


$options = array('load'=> URL .'notes/notes?obj_id='.$this->item['id'].'&obj_type=customer',
		'settings' => array(
			'axisX' => 'right'
		)
	);

if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){
	
	$actions[] = array(
		'text' => 'แก้ไข',
		'attr' => array('data-type'=>'edit'),
		'icon' => 'pencil'
	);

	$actions[] = array('type' => 'separator');

	$actions[] = array(
		'text' => 'ลบ',
		'attr' => array('data-type'=>'remove'),
		'icon' => 'remove'
	);
	$del_url = URL .'notes/del_note';

	$options['actions'] = $actions;
	$options['edit_post_url'] = URL .'notes/edit_note';
	$options['del_post_url'] = URL .'notes/del_note';
}


?><div>
	
	<div id="posts" class="posts" data-plugins="posts" data-options="<?=$this->fn->stringify( $options )?>">
		
		<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
		<form class="post post-form" method="post" action="<?=URL?>notes/save_note">
			<div class="post-form--loader"></div>
			<input type="hidden" name="obj_id" value="<?=$this->item['id']?>" />
			<input type="hidden" name="obj_type" value="customer" />
			<div class="post-form--content post-form--input">
				
				<div class="title-field"></div>
				<div class="editor-wrapper"><textarea data-plugins="autosize" name="text" class="inputtext js-input"></textarea></div>
			</div>

			<div class="post-form--bottom">
				<div class="post-form--controls">
					<div class="control left"></div>
					<div class="control right"><button class="btn btn-blue">Save</button></div>
				</div>
				<div class="post-form--error-bar"></div>
			</div>
		</form>
		<?php } ?>

		<div class="post post-empty">
			<div class="empty">
				<div class="post-loader empty-loader">
					<div class="loader-spin-wrap"><div class="loader-spin"></div></div>
					<div>กำลังโหลด...</div>
				</div>
				<div class="empty-text"></div>
			</div>
		</div>
	</div>
</div>