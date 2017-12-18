<?php

# title
$title = $this->lang->translate('Weight');
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("weight_dw")
    	->label($this->lang->translate('DW').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['dw'])? $this->item['dw']:0 );

$form 	->field("weight_nw")
    	->label($this->lang->translate('NW').'*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['nw'])? $this->item['nw']:0 );
        
# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/save_weight"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);