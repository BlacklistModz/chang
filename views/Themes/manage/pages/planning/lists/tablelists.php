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

        $qty = explode('.', $item['total_qty']);
        $qty_str = $qty[1]==00 ? number_format($item['total_qty'])  : $item['total_qty'];

        $dateStr = $this->fn->q('time')->str_event_date($item['start_date'], $item['end_date']);
        $yearStr = date("Y", strtotime($item['end_date']))+543;

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="name">'.
                '<div class="ellipsis"><a title="'.$item['type_name'].'" class="fwb" href="'.URL.'planning/'.$item['id'].'">'.$item['type_name'].'</a></div>'.
                '<div class="date-float fsm fcg">เพิ่มเมื่อ: '. ( $item['created'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['created'] ):'-' ) .'</div>'.

                '<div class="date-float fsm fcg">แก้ไขล่าสุด: '. ( $item['updated'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['updated'] ):'-' ) .'</div>'.

            '</td>'.

            '<td class="ID">'.$item['week'].'</td>'.

            '<td class="email">'.$dateStr.' '.$yearStr.'</td>'.

            // '<td class="email">'.( $item['updated'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['updated'] ):'-' ).'</td>'.

            '<td class="status">'.$qty_str.'</td>'.

            '<td class="actions">
                <div class="group-btn whitespace">
                    <a class="btn btn-no-padding btn-orange" href="'.URL.'planning/edit/'.$item['id'].'"><i class="icon-pencil"></i></a>
                    <a class="btn btn-no-padding btn-red" data-plugins="dialog" href="'.URL.'planning/del/'.$item['id'].'"><i class="icon-trash"></i></a>
                </div>
            </td>';

        '</tr>';

    }

}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
