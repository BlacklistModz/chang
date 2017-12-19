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

            '<td class="number">'.sprintf("%03d", $item['id']).'</td>'.

            '<td class="name">'.

                '<div class="anchor clearfix">'.
                    $image.
                    
                    '<div class="content"><div class="spacer"></div><div class="massages">'.

                        '<div class="name"><a class="fwb">'. $item['name'].'</a> <span class="fwn">@'. $item['username'].'</span></div>'.

                        '<div class="fsm fcg whitespace">Last update: '.$this->fn->q('time')->live( $item['updated'] ).'</div>'.
                    '</div>'.
                '</div></div>'.

            '</td>'.

            '<td class="actions">'.
                '<span class="gbtn">'.
                    '<a class="btn btn-orange btn-no-padding" href="'.URL.'customers/edit/'.$item['id'].'"><i class="icon-pencil"></i></a>'.
                '</span>'.
            '</td>'.
              
        '</tr>';
        
    }
}

$table = '<table class="settings-table"><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';