<?php


$this->permit = array(
	'add' => !empty($this->permit['customers']['add']),
	'edit' => 0
);


$title = array(
	0 =>   
	  array('key'=>'check-box', 'text'=> '' ),
	  array('key'=>'status', 'text'=>$this->lang->translate('Status') ),
	  array('key'=>'name', 'text'=>$this->lang->translate('Name'),'sort'=>'first_name')
	// , array('key'=>'status', 'text'=>$this->lang->translate('Nick Name') )
	, array('key'=>'express', 'text'=>$this->lang->translate('Contact') )

	
	, array('key'=>'company', 'text'=>$this->lang->translate('Company') )
	// , array('key'=>'address', 'text'=>$this->lang->translate('Address') )

	, array('key'=>'date', 'text'=>$this->lang->translate('Create Date'))
	, array('key'=>'actions', 'text'=>'' )
	
);

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'customers';