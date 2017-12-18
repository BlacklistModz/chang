<?php

$form = new Form();
$form = $form->create()
		->url(URL."settings/company?run=1")
		->addClass('js-submit-form form-insert')
		->method('post');

$form  	->field("name")
		->label($this->lang->translate('Company Name'))
		->addClass('inputtext')
		->required(true)
		->autocomplete("off")
		->value( !empty($this->system['name']) ? $this->system['name']:'' );

$form  	->field("title")
		->label($this->lang->translate('Company Name (English)'))
		->addClass('inputtext')
		->required(true)
		->autocomplete("off")
		->value( !empty($this->system['title']) ? $this->system['title']:'' );

$form  	->field("address")
		->label($this->lang->translate('Address'))
		->type('textarea')
		->addClass('inputtext')
		->autocomplete("off")
		->attr('data-plugins', 'autosize')
		->value( !empty($this->system['address']) ? $this->system['address']:'');

$form  	->field("phone")
		->label($this->lang->translate('Phone'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['phone']) ? $this->system['phone']:'');

$form  	->field("mobile_phone")
		->label($this->lang->translate('Mobile Phone'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['mobile_phone']) ? $this->system['mobile_phone']:'');

$form  	->field("fax")
		->label($this->lang->translate('Fax'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['fax']) ? $this->system['fax']:'');

$form  	->field("license")
		->label($this->lang->translate('License No.'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['license']) ? $this->system['license']:'');

$form  	->field("email")
		->label($this->lang->translate('Email'))
		->addClass('inputtext')
		->autocomplete("off")
		->value( !empty($this->system['email']) ? $this->system['email']:'');

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();
?>
