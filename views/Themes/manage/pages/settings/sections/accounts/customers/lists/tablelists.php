<?php

$tr = "";
$tr_total = "";
$url = URL .'customers/';
if( !empty($this->results['lists']) ){

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) {


        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name
        $image = '';

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="number">'.$item['id'].'</td>'.
            '<td class="name">'.$item['name'].'</td>'.

            '<td class="actions">'.
              '<div class="group-btn whitespace mts">'.
                '<span class="gbtn">'.
                  '<a class="btn btn-orange btn-no-padding" href="'.URL.'settings/accounts/setCustomer/'.$item['id'].'"><i class="icon-pencil"></i></a>'.
                '</span>'.
                '<span class="gbtn">'.
                  '<a class="btn btn-red btn-no-padding" data-plugins="dialog" href="'.URL.'customers/del/'.$item['id'].'"><i class="icon-trash"></i></a>'.
                '</span>'.
              '</div>'.
            '</td>'.

        '</tr>';

    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
