<?php

$a = array();
$a[] = array('label'=>'ชื่อ', 'key' => 'fullname');
$a[] = array('label'=>'ชื่อเล่น', 'key' => 'nickname');
$a[] = array('label'=>'บัตรประชาชน', 'key' => 'card_id');
if( $this->item['birthday'] != '0000-00-00' ){
	$a[] = array('label'=>'เกิด', 'key' => 'birthday');
}
// $a[] = array('label'=>'สถานภาพการสมรส', 'key' => 'fullname');

?>
<section class="mbl">
	<header class="clearfix">
		<h2 class="title"><i class="icon-address-card-o mrs"></i>ข้อมูลพื้นฐาน</h2>
		<?php if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){ ?>
		<a data-plugins="dialog" href="<?=URL?>customers/edit_basic/<?=$this->item['id']?>" class="btn-icon btn-edit"><i class="icon-pencil"></i></a>
		<?php } ?>
	</header>
	
	<table cellspacing="0"><tbody><?php

	foreach ($a as $key => $value) {
		
		if( empty($this->item[ $value['key'] ]) ) continue;

		
		if($value['key']=='birthday'){
			$val =  $this->fn->q('time')->birthday($this->item[ $value['key'] ]);
		}
		else{
			$val = $this->item[ $value['key'] ];
		}
		echo '<tr>'.
			'<td class="label">'.$value['label'].'</td>'.
			'<td class="data">'.$val.'</td>'.
		'</tr>';
	}
	?></tbody></table>
					
</section>