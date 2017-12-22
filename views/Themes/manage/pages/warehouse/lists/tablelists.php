<?php
$total_all_pallet = 0; 
foreach ($this->item['rows']['lists'] as $key => $value) {
  $total_all_pallet += ($value['deep'] * 6);
}
?>
<div ref="header" class="listpage2-header clearfix">
  <div ref="actions" class="listpage2-actions">
    <div id="mainContainer" class="profile clearfix" data-plugins="main">
      <div class="setting-content" role="content">
        <div class="setting-main" role="main">
          <div class="pas pam">
            <div class="clearfix mbm">


              <div class="setting-title">
                <ul class="lfloat" ref="action">
                  <li class="mt">
                    <div class="span10">
                      <div class="uiBoxWhite pas pam">
                        <i class="icon-wareh _ico-center"></i> <span style="position :absolute;">  Zone : <?=$this->item['name']?></span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>


              <div class="clearfix">

                <div class="span10">
                  <div class="uiBoxWhite">
                    <div class="pas pam">
                      <h3 class="mbm fwb"><i class="icon-cube"></i> ข้อมูลแถว (Rows)</h3>
                      <ul>

                        <li>
                          <label><span class="fwb"><i class="icon-building-o"></i> แถวทั้งหมด : <?= $this->item['row_total']?> แถว</label>
                          </li><br>
                          <div class="clearfix">
                            <ul class="lfloat">

                              <?php foreach ($this->_type as $key => $value) { ?>
                              <li><i class="icon-<?=$value['icon']?> _ico-center" style="width: 2.25em;"></i> <span><?=$value['name']?> : <span class="fwb"><?=number_format($value['total'])?></span> UNIT</span>
                              </li>
                              <?php } ?>

                            </ul>
                          </div>

                          <?php 
                          if( !empty($this->item['rows']['lists']) ) { 
                          $percent=$this->total_pallet/$total_all_pallet * 100; 

                          if($percent < 80){
                            $percent_colour="background: rgb(30,87,153);
                            background: -moz-linear-gradient(top, rgba(30,87,153,1) 0%, rgba(41,137,216,1) 50%, rgba(32,124,202,1) 51%, rgba(125,185,232,1) 100%);
                            background: -webkit-linear-gradient(top, rgba(30,87,153,1) 0%,rgba(41,137,216,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%);
                            background: linear-gradient(to bottom, rgba(30,87,153,1) 0%,rgba(41,137,216,1) 50%,rgba(32,124,202,1) 51%,rgba(125,185,232,1) 100%);
                            " ; 
                          }

                          if($percent>= 80){
                            $percent_colour =" background: rgb(248,80,50); background: -moz-linear-gradient(top, rgba(248,80,50,1) 0%, rgba(241,111,92,1) 50%, rgba(246,41,12,1) 51%, rgba(240,47,23,1) 71%, rgba(231,56,39,1) 100%); background: -webkit-linear-gradient(top, rgba(248,80,50,1) 0%,rgba(241,111,92,1) 50%,rgba(246,41,12,1) 51%,rgba(240,47,23,1) 71%,rgba(231,56,39,1) 100%); background: linear-gradient(to bottom, rgba(248,80,50,1) 0%,rgba(241,111,92,1) 50%,rgba(246,41,12,1) 51%,rgba(240,47,23,1) 71%,rgba(231,56,39,1) 100%); ";
                          }?>
                          <style type="text/css">
                          .outter {
                            height: 25px;
                            width: 600px;
                            border: solid 1px #8c8c8c;
                            border-radius: 15px;
                          }
                          .inner {
                            height: 23px;
                            width: <?php echo $percent?>%;
                            border-right: solid 1px #8c8c8c;
                            border-radius: 15px;
                            <?=$percent_colour?>
                          }
                        </style>
                        <h3 class="mbm fwb mtl">สถานะความจุของโกดัง</h3>
                        <div class="outter">
                          <div class="inner" style="text-align: center;">
                            <?php echo round($percent,2)?>%</div>
                        </div>
                        <?php } ?>

                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pas pam">
              <div class="clearfix mbm">
                <div class="span10">

                  <div class="lfloat">
                    <div class="setting-title"><i class="icon-building-o"></i> แถวทั้งหมดใน Zone :
                      <?=$this->item['name']?></div>
                    </div>
                  </div>

                  <div class="span10">
                    <div class="lfloat">
                      <div class="pas pam">
                        <ul>

                          <?php 
                          $data=array(); 
                          foreach ($this->item['rows']['lists'] as $key => $value) { 
                            $type = substr($value['name'],0,1); 
                            $data[$type][] = $value; 
                          } 

                          foreach($data as $zone => $rows){ ?>
                          <div class="span2">
                            <div class="uiBoxWhite pas pam">
                              <h2>GROUP : <?=$zone?> </h2>
                              <?php foreach ($rows as $key=> $value) {?>
                              <a href="<?=URL?>warehouse/showRow/<?=$value['id']?>" class="btn btn-zline">
                                <?=$value[ 'name'];?>
                              </a>

                              <?php } 
                              echo '</div>
                            </div>'; 
                          } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>