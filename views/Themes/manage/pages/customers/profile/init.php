<?php 

$this->tab = isset($this->tab)? $this->tab: '';

$this->tabs = array();
$this->tabs[] = array('id'=>'services','name'=>'ประวัติเข้ารับบริการ', 'icon'=>'handshake-o');
$this->tabs[] = array('id'=>'booking','name'=>'ประวัติการจอง', 'icon'=>'address-book-o', 'count'=>3);
$this->tabs[] = array('id'=>'history','name'=>'ประวัติการเป็นสมาชิก', 'icon'=>'address-card-o');

$this->tabs_right = array();
// $this->tabs_right[] = array('id'=>'files','name'=>'Files', 'icon'=>'file-o');
// $this->tabs_right[] = array('id'=>'plans','name'=>'นัดหมาย', 'icon'=>'history', 'active'=>1);
$this->tabs_right[] = array('id'=>'notes','name'=>'Notes', 'icon'=>'comments-o', 'active'=>1);