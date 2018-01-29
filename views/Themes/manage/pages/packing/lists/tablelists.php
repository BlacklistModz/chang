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

<<<<<<< HEAD
    // '</td>'.

    '<td class="date fwb">'.date("d/m", strtotime($item['date'])).'</td>'.
    '<td class="number fwb">'.$item['job_code'].'</td>'.
    '<td class="number fwb">'.sprintf("%05d",$item['id']).'</td>'.

    '<td class="email">'.$item['ship'].'</td>'.
    '<td class="email">'.$item['shipper'].'</td>'.
    '<td class="type fwb">'.$item['status']['name'].'</td>'.
=======
            // '</td>'.
            // '<td class="number fwb"><a href="'.URL.'packing/add/'.$item['id'].'">'.sprintf("%05d",$item['id']).'</a></td>'.

            '<td class="date fwb">'.date("d/m", strtotime($item['date'])).'</td>'.
            '<td class="name fwb">'.$item['job_code'].'</td>'.
            '<td class="number fwb"><a href="'.URL.'packing/add/'.$item['id'].'">'.sprintf("%05d",$item['id']).'</a></td>'.
>>>>>>> 6cd2a6e83624c726ce4eb585f22d3f57b1f69051

    '<td class="actions">
    <div class="group-btn whitespace">
    <span class="gbtn">
    <a class="btn btn-no-padding btn-green" href="'.URL.'packing/add/'.$item['id'].'"><i class="icon-gift"></i></a>
    </span>
    </div>
    </td>';

    '</tr>';

  }

}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
