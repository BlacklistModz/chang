<?php


$title = array(
	0 =>   
	  array('key'=>'check-box', 'text'=> '<label class="checkbox"><input id="checkboxes" type="checkbox"></label>' ),
	  array('key'=>'name', 'text'=>$this->lang->translate('Name'),'sort'=>'first_name'),
	// , array('key'=>'status', 'text'=>$this->lang->translate('Nick Name') )
	// , array('key'=>'express', 'text'=>$this->lang->translate('Contact') )
	array('key'=>'company', 'text'=>$this->lang->translate('Company') ),
	  array('key'=>'type', 'text'=>$this->lang->translate('Country') ),
	// , array('key'=>'address', 'text'=>$this->lang->translate('Address') )

	array('key'=>'actions', 'text'=>'' ),
	 // , array('key'=>'date', 'text'=>$this->lang->translate('Last Update'))
	
);

// $this->titleStyle = 'row-2';

$this->tabletitle = $title;
$this->getURL =  URL.'customers/newcomers?status=newcomers';