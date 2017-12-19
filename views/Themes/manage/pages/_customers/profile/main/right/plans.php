<?php

$options = array(
	// 'url' => URL."media/lists?obj_type=cus_file&obj_id={$this->item['id']}",
	// 'upload_url' => URL."media/set?obj_type=cus_file&obj_id={$this->item['id']}",
	// 'remove_url' => URL."media/del/",
	'upcoming' => array(
		'url' => URL."events/upcoming?obj_type=customers&obj_id={$this->item['id']}&invite=0&view_stype=bucketed&upcoming",
		
		'options' => array(
			'limit' => 3
		)
	)
);

if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){
	$options['add_url'] = URL."events/add?obj_type=customers&obj_id={$this->item['id']}";
	$options['edit_url'] = URL."events/edit&invite=0";
	$options['remove_url'] = URL."events/del";
}

?><div id="posts" data-plugins="listplan" data-options="<?=$this->fn->stringify($options);?>">
<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
	<button class="right-section-btnTopLink btn btn-small btn-green js-add" type="button" role="add">
		<i class="icon-plus"></i><span class="btn-text mls">เพิ่มใหม่</span>
		<div class="loader-spin-wrap"><div class="loader-spin"></div></div>
	</button>
<?php } ?>
	<div ref="upcoming" class="has-loading">
		<ul class="right-section-listing ui-list-plan" ref="listsbox"></ul>

		<a class="ui-more btn" role="more">โหลดเพิ่มเติม</a>
		<div class="ui-alert">
			<div class="ui-alert-loader">
				<div class="ui-alert-loader-icon loader-spin-wrap"><div class="loader-spin"></div></div>
				<div class="ui-alert-loader-text">กำลังโหลด...</div> 
			</div>

			<div class="ui-alert-error">
				<div class="ui-alert-error-icon"><i class="icon-exclamation-triangle"></i></div>
				<div class="ui-alert-error-text">ไม่สามารถเชื่อมต่อได้</div> 
			</div>

			<div class="ui-alert-empty">
				<div class="ui-alert-empty-text">ไม่มีนัดหมาย 
				<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
				<a class="js-add">เพิ่มนัดหมาย</a>
				<?php } ?>
				</div> 
			</div>
		</div>
	</div>
	
</div>
