<?php

$body = '';
for($i=10;$i>=1;$i--){
  $td = '';
  for($j=$this->item['deep'];$j>=1;$j--){
    if( empty($this->item['pallets'][$j][$i]) ) {
      $pallet = '<div>
                    <ul calss="lfloat" ref="action" style="height: 40px;">
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
    $td .= '<td><div class="'.$className.'" style="position: relative;   position: relative; border: 2px solid #bfbfbf; padding-bottom: 12px; width: 98px;">'.
      '<i class="icon-boxx  _ico-center64"></i>'.$pallet.
      '</div></td>';
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
							<table class="blueTable"><?=$body?></table>

						</div>
					</div>

		</div>
	</div>
</div>
