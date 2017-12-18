<?php

#Order
// $order[] = array('key'=>'planning', 'text'=>'แผนการผลิต', 'link'=>$url.'planning', 'icon'=>'angle-double-right ');
foreach ($this->type as $key => $value) {
	$order[] = array('key'=>'pallets-'.$value['id'], 'text'=>'ผลิต'.$value['name'].' ('.$value['code'].')', 'link'=>$url.'pallets/'.$value['id'], 'icon'=>$value['icon'].' _ico-center');
}

// foreach ($order as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($order[$key]);
// }
if( !empty($order) ){
	echo $this->fn->manage_nav($order, $this->getPage('on'));
}

#info
/* $info[] = array('key'=>'dashboard','text'=>$this->lang->translate('menu','Dashboard'),'link'=>$url.'dashboard','icon'=>'home');
$info[] = array('key'=>'calendar','text'=>$this->lang->translate('menu','Calendar'),'link'=>$url.'calendar','icon'=>'calendar');
foreach ($info as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($info[$key]);
}
if( !empty($info) ){
	echo $this->fn->manage_nav($info, $this->getPage('on'));
} */


#Customer
/* $cus[] = array('key'=>'customers','text'=>$this->lang->translate('menu','Customers'),'link'=>$url.'customers','icon'=>'address-card-o');
	if( empty($this->permit[$value['key']]['view']) ) unset($cus[$key]);
if( !empty($cus)){
	echo $this->fn->manage_nav($cus, $this->getPage('on'));
} */

#reports
// $reports[] = array('key'=>'projects','text'=>$this->lang->translate('menu','Projects'),'link'=>$url.'projects','icon'=>'book');
/*$reports[] = array('key'=>'tasks','text'=>$this->lang->translate('menu','Tasks'),'link'=>$url.'tasks','icon'=>'check-square-o');
$reports[] = array('key'=>'reports','text'=>$this->lang->translate('menu','Reports'),'link'=>$url.'reports','icon'=>'line-chart');
foreach ($reports as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($reports[$key]);
}
if( !empty($reports) ){
	echo $this->fn->manage_nav($reports, $this->getPage('on'));
} */

foreach ($this->zone['lists'] as $key => $value) {
	$zone[] = array('key'=>'zone-'.$value['id'], 'text'=>'Zone '.$value['name'], 'link'=>$url.'warehouse/'.$value['id'], 'icon'=>('wareh').' _ico-center');
}
if( !empty($zone) ){
	echo $this->fn->manage_nav($zone, $this->getPage('on'));
}

$cog[] = array('key'=>'settings','text'=>$this->lang->translate('menu','Settings'),'link'=>$url.'settings','icon'=>'cog');
echo $this->fn->manage_nav($cog, $this->getPage('on'));
