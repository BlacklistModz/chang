<?php

$title[] = array('key'=>'ID', 'text'=>'ID', 'sort'=>'id');
$title[] = array('key'=>'date', 'text'=>'วันที่ผลิต', 'sort'=>'date');
$title[] = array('key'=>'name', 'text'=>'PALLET CODE', 'sort'=>'code');
$title[] = array('key'=>'status', 'text'=>'จำนวน(กระป๋อง)', 'sort'=>'qty');
$title[] = array('key'=>'status', 'text'=>'SIZE');
$title[] = array('key'=>'status', 'text'=>'WEIGHT');
// $title[] = array('key'=>'status', 'text'=>'BRAND');
// $title[] = array('key'=>'status', 'text'=>'NECK');
$title[] = array('key'=>'status', 'text'=>'ฝา');
$title[] = array('key'=>'status', 'text'=>'Grade/สูตร');
$title[] = array('key'=>'status', 'text'=>'ความอ่อน/แก่');

$title[] = array('key'=>'status', 'text'=>'ความหวาน');
$title[] = array('key'=>'status', 'text'=>'ZONE', 'sort'=>'ware_id');
$title[] = array('key'=>'status', 'text'=>'แถว', 'sort'=>'row_id');
$title[] = array('key'=>'status', 'text'=>'ตั้ง/ลึก', 'sort'=>'deep');
$title[] = array('key'=>'status', 'text'=>'ชั้น', 'sort'=>'floor');
$title[] = array('key'=>'actions', 'text'=>'');

$this->tabletitle = $title;
$this->getURL =  URL.'pallets/'.$this->item['id'];