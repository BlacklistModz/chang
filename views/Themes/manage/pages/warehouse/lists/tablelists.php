
<div ref="header" class="listpage2-header clearfix">
  <div ref="actions" class="listpage2-actions">
    <div id="mainContainer" class="profile clearfix" data-plugins="main">
      <div class="setting-content" role="content">
        <div class="setting-main" role="main">
          <div class="pas pam">
            <div class="clearfix mbm">
              <div class="span11">

                  <div class="setting-title">
                    <ul class="lfloat" ref="action"><li class="mt">

                    <i class="icon-wareh _ico-center"></i> <span>  Zone : <?=$this->item['name']?></span>

                </li>
                  </ul>
                  </div>

              </div>
              <div class="clearfix">

                <div class="span10">
                  <div class="uiBoxWhite">
                    <h3 class="mbm fwb"><i class="icon-cube"></i> ข้อมูลแถว (Rows)</h3>
                    <ul>

                      <li>
                        <label><span class="fwb"><i class="icon-building-o"></i> แถวทั้งหมด : <?= $this->item['row_total']?></label>
                        </li>


                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pas pam">
              <div class="clearfix mbm">
                <div class="span10">
                  <div class="lfloat">
                    <div class="setting-title"><i class="icon-building-o"></i> แถวทั้งหมดใน Zone : <?=$this->item['name']?></div>
                  </div>
                </div>

                <div class="span10">
                  <div class="lfloat">
                    <ul>
                      <?php
                      foreach ($this->item['rows']['lists'] as $key => $value) {?>

                        <ul class="lfloat" ref="actions">
                          <li class="divider"></li>

                          <li class="mt">
                            <div class="rfloat">
                              <a href="<?=URL?>warehouse/showRow/<?=$value['id']?>" data-plugins="dialog" class="btn btn-zline">
                                <span><?=$value['name']?></span></a>
                              </div>
                            </li>

                          </ul>

                        <?php	}?>


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
