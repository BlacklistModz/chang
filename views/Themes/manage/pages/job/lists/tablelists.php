<?php
//print_r($this->results['lists']); die;
$tr = "";
$tr_total = "";

if( !empty($this->results['lists']) ){
    //print_r($this->results); die;

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) {
        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="ID">'.$item['code'].'</td>'.

            '<td class="date">'.date("d/m/y", strtotime($item['date'])).'</td>'.

            '<td class="name">'.
                '<div class="ellipsis"><a title="" class="fwb" href="'.URL.'job/'.$item['id'].'"></a></div>'.
            '</td>'.

            '<td class="contact"></td>'.

            '<td class="actions">
                <span class="gbtn">
                    <a href="'.URL.'job/edit/'.$item['id'].'" class="btn btn-orange btn-no-padding"><i class="icon-pencil"></i></a>
                </span>
                <span class="gbtn">
                    <a href="'.URL.'job/del/'.$item['id'].'" data-plugins="dialog" class="btn btn-red btn-no-padding"><i class="icon-trash"></i></a>
                </span>
            </td>';

        '</tr>';

    }

}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
