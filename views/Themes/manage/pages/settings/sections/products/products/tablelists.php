<?php

$tr = "";
$tr_total = "";
$url = URL .'products/';

if( !empty($this->results['lists']) ){

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) {

        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="name">'.

                '<div class="anchor clearfix">'.

                    '<div class="content"><div class="spacer"></div><div class="massages">'.

                        '<div class="fullname"><a class="fwb">'. $item['code'].'</a></div>'.


                        // '<div class="fss fcg whitespace">Last update: '.$this->fn->q('time')->live( $item['updated'] ).'</div>'.
                    '</div>'.
                '</div></div>'.

            '</td>'.

            '<td class="icon_name">'.$item['amount'].'</td>'.

            '<td class="actions whitespace">
                <span class=""><a data-plugins="dialog" href="'.$url.'edit/'.$item['id'].'" class="btn btn-no-padding btn-orange"><i class="icon-pencil"></i></a></span>
                <span class=""><a data-plugins="dialog" href="'.$url.'del/'.$item['id'].'" class="btn btn-no-padding btn-red"><i class="icon-trash"></i></a></span>
            </td>'.

        '</tr>';

    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
