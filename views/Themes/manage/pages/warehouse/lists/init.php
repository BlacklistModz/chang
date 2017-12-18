<?php

$title[] = array('key'=>'ID', 'text'=>'ID', 'sort'=>'id');
$title[] = array('key'=>'name', 'text'=>'Name', 'sort'=>'name');
$title[] = array('key'=>'price', 'text'=>'Price', 'sort'=>'price');
$title[] = array('key'=>'actions', 'text'=>'Action');



// $title = array( 0 =>
// 	array(
// 	0 =>   array('key'=>'date', 'text'=>'เพิ่มเมื่อ', 'sort'=>'created','rowspan'=>2)

// 		 , array('key'=>'name', 'text'=>'ชื่อ-นามสกุล','sort'=>'first_name','rowspan'=>2)
// 		 , array('key'=>'email', 'text'=>'ข้อมูลการติดต่อ','rowspan'=>2)
// 		 , array('key'=>'status', 'text'=>'จำนวน', 'colspan'=> 3)
// 	),
// 	array(
// 	0 =>   array('key'=>'status', 'text'=>'รถ')
// 		 , array('key'=>'status', 'text'=>'การจอง')
// 		 , array('key'=>'status', 'text'=>'ยกเลิกจอง')
// 	)

// );

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'warehouse';
