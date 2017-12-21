<?php

# title
$title = 'Retort';
$arr['title']= $title;
if( !empty($this->item) ){
	$arr['hiddenInput'][] = array('name'=>'id','value'=>$this->item['id']);
}


$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$form 	->field("rt_name")
    		->label('ชื่อ*')
        ->autocomplete('off')
        ->addClass('inputtext')
        ->placeholder('')
        ->value( !empty($this->item['name'])? $this->item['name']:'' );

// if( !empty($this->status) ){
// 	$form 	->field("brand_status")
//     		->label('สถานะ*')
//         	->autocomplete('off')
//         	->addClass('inputtext')
//         	->placeholder('')
//         	->select( $this->status )
//         	->value( !empty($this->item["status"]) ? $this->item["status"] : "" );
// }

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'pallets/save_retort"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

echo json_encode($arr);
