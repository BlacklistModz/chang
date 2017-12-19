<?php

$body = '';
for($i=10;$i>=1;$i--){
  $td = '';
  for($j=$this->item['deep'];$j>=1;$j--){
    if( empty($this->item['pallets'][$j][$i]) ) {
      $pallet = '<div>
                    <ul calss="lfloat" ref="action" style="height: 66px;">
                      <li class="mt">
                        <i></i>
                      </li>
                    </ul>
                  </div>';
                  $className = null;
    }
    else{
      $className ="_isNotempty";
      $_icon = '';
      foreach ($this->item['pallets'][$j][$i]['icon'] as $icon) {
        $_icon .= '<i class="icon-'.$icon['type_icon'].' _ico-center"></i>';

      }
      $pallet = '<a target="_blank" href="'.URL.'pallets/profile/'.$this->item['pallets'][$j][$i]['id'].'"><div> Pallet code '.$this->item['pallets'][$j][$i]['code'].'<br><br>'.$_icon.'</div></a>' ;
    }
    $td .= '<td><div class="'.$className.'" style="position: relative;   position: relative; border: 2px solid #bfbfbf; padding-bottom: 10px; width: 140px;">'.
      '<i class="icon-boxx  _ico-center64"></i>'.$pallet.
      '</div></td>';
  }
  $body .= '<tr>'.$td.'</tr>';
}


$arr['title'] = 'แถว '.$this->item['name'];
$arr['body'] = '<svg xmlns="http://www.w3.org/2000/svg" style="margin:300px 5px 5px 0px; position: absolute;" width="120" height="120"> <text transform="rotate(270, 12, 0) translate(-105,0)">จำนวนของชั้น 1..N &#8594;</text></svg>'
      .'<table class="blueTable">'.$body.'</table><br>
      <p style="margin: -23px 0px 10px 35px;">ความลึกของแถว N..1 &#8594;</p></div>';
$arr['bottom_msg'] = '<a class="btn" role="dialog-close"><span class="btn-text">Cancel</span></a>';
$arr['is_close_bg'] = true;
$arr['width'] = 'full';
$arr['height'] = 'full';



echo json_encode($arr);

?>
