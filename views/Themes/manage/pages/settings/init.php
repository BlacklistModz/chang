<?php

$this->count_nav = 0;

/* System */
$sub = array();
$sub[] = array('text' => $this->lang->translate('Company'),'key' => 'company','url' => URL.'settings/company');
// $sub[] = array('text'=>'Dealer','key'=>'dealer','url'=>URL.'settings/dealer');
$sub[] = array('text' => $this->lang->translate('Profile'),'key' => 'my','url' => URL.'settings/my');

foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
}
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text' => '', 'url' => URL.'settings/company', 'sub' => $sub);
}

/* Accounts */
$sub = array();
$sub[] = array('text'=> $this->lang->translate('Department'),'key'=>'department','url'=>URL.'settings/accounts/department');
$sub[] = array('text'=> $this->lang->translate('Position'),'key' => 'position','url' => URL.'settings/accounts/position');
$sub[] = array('text'=> $this->lang->translate('Employees'),'key' => 'employees','url' => URL.'settings/accounts/');
$sub[] = array('text'=> $this->lang->translate('Customers'),'key' => 'customers','url' => URL.'settings/accounts/customers');
$sub[] = array('text'=> $this->lang->translate('Customers brand'),'key' => 'customers_brands','url' => URL.'settings/accounts/customers_brands');


// foreach ($sub as $key => $value) {
// 	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
// }
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Accounts'),'sub' => $sub, 'url' => URL.'settings/accounts/');
}

/* Products */
$sub = array();
$sub[] = array('text'=> 'สินค้า','key'=>'products','url'=>URL.'settings/products/products');
$sub[] = array('text'=> 'ประเภท/ผลไม้','key'=>'type','url'=>URL.'settings/products/type');
$sub[] = array('text'=> 'พันธุ์ผลไม้', 'key'=>'breed', 'url'=>URL.'settings/products/breed');
$sub[] = array('text'=> 'GRADE/สูตร', 'key'=>'grade', 'url'=>URL.'settings/products/grade');
$sub[] = array('text'=> 'ความอ่อน/แก่ผลไม้', 'key'=>'old', 'url'=>URL.'settings/products/old');
$sub[] = array('text'=> 'ขนาด','key'=>'size','url'=>URL.'settings/products/size');
$sub[] = array('text'=> 'น้ำหนัก (DW/NW)','key'=>'weight','url'=>URL.'settings/products/weight');
$sub[] = array('text'=> 'แบนด์','key'=>'brand','url'=>URL.'settings/products/brand');
$sub[] = array('text'=> 'กระป๋อง','key'=>'can','url'=>URL.'settings/products/can');
$sub[] = array('text'=> 'ชนิดกระป๋อง','key'=>'canType','url'=>URL.'settings/products/canType');

/* foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
} */
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> $this->lang->translate('Products'),'sub' => $sub, 'url' => URL.'settings/products/');
}

/* Warehouse */
$sub = array();
$sub[] = array('text'=> 'โกดัง (Zone)','key'=>'lists','url'=>URL.'settings/warehouse/lists');
$sub[] = array('text'=> 'แถว','key'=>'rows','url'=>URL.'settings/warehouse/rows');

/* foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
} */
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> 'WareHouse','sub' => $sub, 'url' => URL.'settings/warehouse/');
}

/* Pallets */
$sub = array();
$sub[] = array('text'=> 'ยี่ห้อพาเลท','key'=>'brands','url'=>URL.'settings/pallets/brands');
$sub[] = array('text'=> 'Retort','key'=>'retort','url'=>URL.'settings/pallets/retort');


/* foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
} */
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> 'พาเลท','sub' => $sub, 'url' => URL.'settings/pallets/');
}

/* Pallets */
$sub = array();
$sub[] = array('text'=> 'สาเหตุการโฮล','key'=>'cause','url'=>URL.'settings/hold/cause');
$sub[] = array('text'=> 'การจัดการโฮล','key'=>'manage','url'=>URL.'settings/hold/manage');

/* foreach ($sub as $key => $value) {
	if( empty($this->permit[$value['key']]['view']) ) unset($sub[$key]);
} */
if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> 'HOLD','sub' => $sub, 'url' => URL.'settings/hold/');
}

// platform//////////////////////////////////////////////////////////////////
$sub = array();
$sub[] = array('text'=> 'ชานชาลา','key'=>'platform','url'=>URL.'settings/planload/platform');

if( !empty($sub) ){
	$this->count_nav+=count($sub);
	$menu[] = array('text'=> 'PLANLOAD','sub' => $sub, 'url' => URL.'settings/planload/');
}
