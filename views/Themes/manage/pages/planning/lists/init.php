<?php

$title[] = array('key'=>'name', 'text'=>'รายการผลิต');
$title[] = array('key'=>'ID', 'text'=>'สัปดาห์', 'sort'=>'week');
$title[] = array('key'=>'email', 'text'=>'ระหว่างวันที่');
// $title[] = array('key'=>'email', 'text'=>'แก้ไขเมื่อ');
$title[] = array('key'=>'status', 'text'=>'จำนวนตู้', 'sort'=>'total_qty');
$title[] = array('key'=>'actions', 'text'=>'');

$this->tabletitle = $title;
$this->getURL =  URL.'planning/';