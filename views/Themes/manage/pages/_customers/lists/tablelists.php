<?php

$tr = "";
$tr_total = "";
if( !empty($this->results['lists']) ){ 

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 


        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";

        $image = '';
        if( !empty($item['image_url']) ){
            $image = '<div class="avatar lfloat mrm"><img class="img" src="'.$item['image_url'].'" alt="'.$item['fullname'].'"></div>';
        }
        else{
            $image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
        }

        $qty = !empty($item['total_item'])
            ? $item['total_item']
            : '-';


        $express = '';
        if( !empty($item['phone']) ){
            $express .= '<li><i class="icon-phone mrs"></i><a href="tel:'.$item['phone'].'">'.$item['phone'].'</a></li>';
        }

        if( !empty($item['email']) ){
             $express .= '<li><i class="icon-envelope mrs"></i><a href="mailto:'.$item['email'].'" title="'.$item['email'].'">'.$item['email'].'</a></li>';
        }

        if( !empty($item['lineID']) ){
            $express .= '<li><i class="icon-line mrs"></i><a href="line:'.$item['lineID'].'">'.$item['lineID'].'</a></li>';
        }

        $age = '';
        if( $item['birthday'] !='0000-00-00' ){
            $age = '<span class="fsm fwn mls meta">(อายุ '.$this->fn->q('time')->age( $item['birthday'] ).' ปี)</sapn>';
        }

        if( !empty($item['nickname']) ){
            $item['fullname'] .=" ({$item['nickname']})";
        }

        $time = strtotime($item['created']);
        $date = date('j', $time) . ' ' . $this->fn->q('time')->month( date('n', $time), true, $this->lang->getCode() ) . ' ' .date('Y', $time);
        // $date .= '<div>'.  .'</div>';

        # company_name
        $company_name = '-';
        if( !empty($item['company_name']) ){
            $group_name = '';

            if( !empty($item['group_name']) ){
                $group_name = '<span class="fsm fcg">('.$item['group_name'].')</span>';
            }
            $company_name = '<span class="fwb mrs">'.$item['company_name'].'</span>'.$group_name;
        }
        

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['id'].'">'.

            '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.

            '<td class="status"><span class="ui-status">'.$item['status']['code'].'</span></td>'.

            '<td class="name">'.
                '<div class="anchor clearfix">'.
                    $image.
                    '<div class="content"><div class="spacer"></div><div class="massages">'.
                        '<div class="fullname"><a class="fwb" href="'.URL .'customers/'.$item['id'].'">'. $item['fullname'].'</a></div>'.
                        '<div class="subname fsm meta fcg">Last update: '.$this->fn->q('time')->live( $item['updated'] ).'</div>'.
                    '</div>'.
                '</div></div>'.
            '</td>'.

            // '<td class="status">'.$item['status'].'</td>'.

            '<td class="express"><ul class="fsm">'.$express.'</ul></td>'.
            '<td class="company">'.

                '<div class="">'.$company_name.'</div>'.
                '<div class="fsm">'.$item['company_address'].'</div>'.
            '</td>'.

            '<td class="date">'.$date.'</td>'.
            
            '<td class="actions"><div class="group-btn whitespace"><a class="btn" data-plugins="dialog" href="'.URL.'customers/edit/'.$item['id'].'"><i class="icon-pencil"></i></a><a class="btn" data-plugins="dialog" href="'.URL.'customers/del/'.$item['id'].'"><i class="icon-trash"></i></a>
                </div></td>'.

        '</tr>';
        
    }
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';