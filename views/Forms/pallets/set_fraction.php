<?php 

$arr['hiddenInput'][] = array('name'=>'id', 'value'=>$this->item['id']);

$arr['title'] = 'รวมเศษ';

$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert');

$form 	->field("lists")
		->label("รวมเศษ *")
		->text('<div role="tableFraction">
					<table class="table-bordered" width="100%">
						<thead>
							<tr>
								<th width="30%">Pallet No.</th>
								<!--<th width="10%">Pallet QTY</th>-->
								<th width="30%">จำนวน</th>
								<th width="20%"></th>
							</tr>
						</thead>
						<tbody role="listsitem"></tbody>
					</table>
				</div>');

$options = $this->fn->stringify( array(
		'pallet'=>$this->pallet['lists']
	) );

# set form
$arr['form'] = '<form class="js-submit-form" method="post" action="'.URL. 'pallets/setFraction" data-plugins="fractionForm" data-options="'.$options.'"></form>';

# body
$arr['body'] = $form->html();

# fotter: button
$arr['button'] = '<button type="submit" class="btn btn-primary btn-submit"><span class="btn-text">Save</span></button>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';

$arr['width'] = 550;

echo json_encode($arr);