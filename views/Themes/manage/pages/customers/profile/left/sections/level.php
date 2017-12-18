<?php

$a = array();
$a[] = array('label'=>'รหัสสมาชิก', 'key'=> 'code');
$a[] = array('label'=>'ระดับ', 'key' => 'level');
$a[] = array('label'=>'สถานะ', 'key' => 'status');
// $a[] = array('label'=>'สถานภาพการสมรส', 'key' => 'fullname');

?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-info-circle mrs"></i>ข้อมูลสมาชิก</h2>
		<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
		<a data-plugins="dialog" href="<?=URL?>customers/edit_cus_level/<?=$this->item['id']?>" class="btn-icon btn-edit"><i class="icon-pencil"></i></a>
		<?php } ?>
	</header>
	
	<table cellspacing="0"><tbody><?php

	foreach ($a as $key => $value) {
		
		if( empty($this->item[ $value['key'] ]) ) continue;

		$val = $this->item[ $value['key'] ];

		if( $val == 'run' && $value['key'] != 'code' ) {
			$val = '<div class="status-wrap"><a class="ui-status" style="background-color: rgb(11, 195, 57);">RUN</a></div>';
		}
		elseif( $val != 'run' && $value['key'] != 'code' ){
			$val = '<div class="status-wrap"><a class="ui-status" style="background-color: rgb(219, 21, 6);">EXPIRED</a></div>';
		}

		if( $value['key'] == 'level' ){
			$val = $this->item[ $value['key'] ]['name'];
		}

		echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
		'</tr>';
	}
	?></tbody></table>
					
</section>