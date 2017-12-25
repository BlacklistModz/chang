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

        $return = $item['cabinet_return'] != "0000-00-00" ? date("d/m", strtotime($item['cabinet_return'])) : "-";
        $closed = $item['closed_date'] != "0000-00-00" ? date("d/m", strtotime($item['closed_date'])) : "-";
        $get = $item['cabinet_get'] != "0000-00-00" ? date("d/m", strtotime($item['cabinet_get'])) : "-";
        $etd = $item['etd_date'] != "0000-00-00" ? date("d/m", strtotime($item['etd_date'])) : "-";

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            // '<td class="name">'.
            //     '<div class="ellipsis"><a title="'.$item['type_name'].'" class="fwb" href="'.URL.'planning/'.$item['id'].'">'.$item['type_name'].'</a></div>'.
            //     '<div class="date-float fsm fcg">เพิ่มเมื่อ: '. ( $item['created'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['created'] ):'-' ) .'</div>'.

            //     '<div class="date-float fsm fcg">แก้ไขล่าสุด: '. ( $item['updated'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['updated'] ):'-' ) .'</div>'.

            // '</td>'.


            '<td class="date fwb">'.date("d/m", strtotime($item['date'])).'</td>'.

            '<td class="email">'.(!empty($item['plat_name']) ? $item['plat_name'] : "-").'</td>'.

            '<td class="name fwb">'.$item['job_code'].'</td>'.
            '<td class="number fwb">'.sprintf("%05d",$item['id']).'</td>'.

            '<td class="date">'.$return.'</td>'.
            '<td class="date">'.$closed.'</td>'.
            '<td class="date">'.$get.'</td>'.
            '<td class="date">'.$etd.'</td>'.

            '<td class="email">'.$item['ship'].'</td>'.
            '<td class="email">'.$item['shipper'].'</td>'.
            '<td class="type fwb">'.$item['status']['name'].'</td>'.

            '<td class="actions">
                <div class="group-btn whitespace">
                    <a class="btn btn-no-padding btn-orange" href="'.URL.'planload/edit/'.$item['id'].'"><i class="icon-pencil"></i></a>
                    <a class="btn btn-no-padding btn-red" data-plugins="dialog" href="'.URL.'planload/del/'.$item['id'].'"><i class="icon-trash"></i></a>
                </div>
            </td>';

        '</tr>';

    }

}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
