<?php
//print_r($this->results['lists']); die;
$tr = "";
$tr_total = "";

if( !empty($this->results['lists']) ){
    //print_r($this->results); die;

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) {

        $dw = !empty($item['weight_dw']) ? $item['weight_dw'] : "-";
        $nw = !empty($item['weight_nw']) ? $item['weight_nw'] : "-";
        $weight = "{$dw}/{$nw}";

        $ware = '<option value="">-</option>';
        foreach ($this->warehouse as $key => $value) {
            $sel = '';
            if( $item['ware_id'] == $value['id'] ) $sel = ' selected="1"';
            $ware .= '<option'.$sel.' value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        $ware = $floor = '<select class="inputtext" data-plugins="_update" data-options="'.$this->fn->stringify(array('url' => URL. 'pallets/setdata/'.$item['id'].'/ware_id')).'" data-name="warehouse">'.$ware.'</select>';

        $floor = '<option value="">-</option>';
        for ($i=1; $i <= 6 ; $i++) {
            $sel = '';
            if( $item['floor'] == $i ) $sel = ' selected="1"';
            $floor .= '<option'.$sel.' value="'.$i.'">'.$i.'</option>';
        }
        $floor = '<select class="inputtext" data-plugins="_update" data-options="'.$this->fn->stringify(array('url' => URL. 'pallets/setdata/'.$item['id'].'/floor')).'">'.$floor.'</select>';

        $row = '<select class="inputtext" data-plugins="_update" data-options="'.$this->fn->stringify(array('url' => URL. 'pallets/setdata/'.$item['id'].'/row_id')).'" data-name="rows"><option value="">-</option></select>';

        $deep = '<select class="inputtext" data-plugins="_update" data-options="'.$this->fn->stringify(array('url' => URL. 'pallets/setdata/'.$item['id'].'/deep')).'" data-name="deep"><option value="">-</option></select>';

        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        if( !empty($item['total_hole']) ){
            $cls .= ' hold';
        }

        $options = $this->fn->stringify( array('currRows'=>$item['row_id'], 'currDeep'=>$item['deep']) );

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'" data-plugins="tablePallets" data-options="'.$options.'">'.



            '<td class="date">'.date("d/m/Y", strtotime($item['date'])).'</td>'.

            '<td class="name">'.
                '<div class="ellipsis"><a title="'.$item['code'].'" class="fwb" href="'.URL.'pallets/profile/'.$item['id'].'">'.(!empty($item['code']) ? $item['code'] : "-").'</a></div>'.

                /* '<div class="date-float fsm fcg">เพิ่มเมื่อ: '. ( $item['created'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['created'] ):'-' ) .'</div>'.
                '<div class="date-float fsm fcg">แก้ไขล่าสุด: '. ( $item['updated'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['updated'] ):'-' ) .'</div>'. */

            '</td>'.

            '<td class="status">'.$item['qty'].'</td>'.

            '<td class="category" style="text-align:center;">'.(!empty($item['size_name']) ? $item['size_name'] : "-").'</td>'.

            '<td class="category" style="text-align:center;">'.$weight.'</td>'.

            '<td class="category" style="text-align:center;">'.(!empty($item['lid']) ? $item['lid'] : "-").'</td>'.
            '<td class="category" style="text-align:center;">'.(!empty($item['grade_name']) ? $item['grade_name'] : "-").'</td>'.
            '<td class="category" style="text-align:center;">'.(!empty($item['old_code']) ? $item['old_code'] : "-").'</td>'.

            '<td class="status">'.(!empty($item['brix_name']) ? $item['brix_name'] : "-").'</td>'.

            '<td class="status">Zone '.$ware.'</td>'.

            '<td class="status">แถว '.$row.'</td>'.

            '<td class="status">ตั้ง '.$deep.'</td>'.

            '<td class="status">ชั้น '.$floor.'</td>'.

            '<td class="actions">
                <div class="group-btn whitespace">'.
                    // '<a class="btn btn-blue" data-plugins="dialog" href="'.URL.'pallets/set_warehouse/'.$item['id'].'"><i class="icon-pencil"></i> ตั้งค่าที่ตั้ง</a>'.
                    '<a class="btn btn-no-padding btn-orange" href="'.URL.'pallets/edit/'.$item['id'].'"><i class="icon-pencil"></i></a>'.
                    '<a class="btn btn-no-padding btn-red" data-plugins="dialog" href="'.URL.'pallets/del/'.$item['id'].'"><i class="icon-trash"></i></a>'.
                '</div>
            </td>';

        '</tr>';

    }

}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';
