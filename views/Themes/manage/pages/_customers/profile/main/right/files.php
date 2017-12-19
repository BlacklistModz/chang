<?php

$options = array(
	'url' => URL."media/lists?obj_type=cus_file&obj_id={$this->item['id']}"
);

if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){
	$options['upload_url'] = URL."media/set?obj_type=cus_file&obj_id={$this->item['id']}";
	$options['remove_url'] = URL."media/del/";
}

?><div id="posts" data-plugins="rupload" data-options="<?=$this->fn->stringify($options);?>">
<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
	<button class="right-section-btnTopLink btn btn-small btn-green js-upload" type="button" rel="add">
		<i class="icon-upload"></i><span class="btn-text mls">เพิ่มไฟล์</span>
		<div class="loader-spin-wrap"><div class="loader-spin"></div></div>
	</button>
<?php } ?>
	<ul class="right-section-listing" rel="listsbox"></ul>
	<div class="alert">
		<div class="alert-loader">
			<div class="alert-loader-icon loader-spin-wrap"><div class="loader-spin"></div></div>
			<div class="alert-loader-text">กำลังโหลด...</div> 
		</div>

		<div class="alert-error">
			<div class="alert-error-icon"><i class="icon-exclamation-triangle"></i></div>
			<div class="alert-error-text">ไม่สามารถเชื่อมต่อได้</div> 
		</div>

		<div class="alert-empty">
			<div class="alert-empty-text">ไม่มีไฟล์ 
			<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
			<a class="js-upload">เพิ่มไฟล์ใหม่</a>
			<?php } ?>
			</div> 
		</div>
	</div>
</div>