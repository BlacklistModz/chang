<?php

# title
$title = $this->item['name'].' ('.$this->_size['name'].')';
$arr['title']= $title;

$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);
$arr['hiddenInput'][] = array('name'=>'sid', 'value'=>$this->_size['id']);

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

$weight = array();
foreach ($this->weight as $key => $value) {
	$checked = false;
	if( !empty($this->results) ){
		foreach ($this->results as $i => $val) {
			if( $val['weight_id'] == $value['id'] ){
				$checked = true;
                break;
			}
		}
	}

	$weight[] = array(
        'text' => $value['dw'].' / '.$value['nw'], //.'('.$value['code'].')',
        'value' => $value['id'],
        'checked' => $checked
    );
}

$form   ->field("weight")
        ->label('เลือกน้ำหนัก')
        ->text('<div data-plugins="selectmany" data-options="'.
        $this->fn->stringify( array(
            'lists' => $weight,
            'name' => 'weight[]',
            'class' => 'inputtext'
        ) ).'"></div>');

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'products/setWeight"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">บันทึก</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">ยกเลิก</span></a>';

echo json_encode($arr);
