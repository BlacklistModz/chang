<?php

$body = '';
for($i=6;$i>=1;$i--){
  $td = '';
  for($j=$this->item['deep'];$j>=1;$j--){
    if( empty($this->item['pallets'][$j][$i]) ) {
      $pallet = '<div>
      <ul calss="lfloat" ref="action" style="height: 68px;">
      <li class="mt">
      <label style="opacity: 0.3;">No Pallet</label>
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
    $td .= '<td><div class="'.$className.'" style="position: relative;   position: relative; border: 2px solid #bfbfbf; padding-bottom: 12px; width: 98px;">'.
    $pallet.'</div></td>';
  }
  $body .= '<tr>'.$td.'</tr>';
}
?>

<div id="mainContainer" class="report-main clearfix" data-plugins="main">
  <div role="content">
    <div role="main" class="pal">
      <div class="uiBoxWhite pas pam">
        <h2>Rows(แถว) : <?=$this->item['name']?></h2>
      </div>

      <div class="uiBoxWhite pas pam mtl">
        <div>
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" style="margin:-15px 5px 5px 70px; position: absolute;" width="30" height="600"><text transform="rotate(270, 12, 0) translate(-435,0)">|--------------------- จำนวนชั้นของ pallet(ชั้น)------------------→</text></svg><br>

            <table class="blueTable"><?=$body?></table>
            <p style="margin: 5px 0px 10px 280px;" width="300">&#8592;-------------------------ความลึกของแถว(ตั้ง)---------------------------|</p>
          </div>
        </div>
      </div>
      <div class="uiBoxWhite pas pam mtl">
        <a href="<?=URL?>warehouse/<?=$this->item['ware_id']?>" class="btn" role="dialog-close"><span class="btn-text">กลับไปยังหน้าเดิม</span></a>
      </div>


    </div>
  </div>
</div>
