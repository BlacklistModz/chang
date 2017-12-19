<?php if( !empty($this->booking) ){ 

    $lists = array();
    foreach ($this->booking as $key => $val) {
        $month = date("Y-m", strtotime($val['start_date']));
        $lists[$month][] = $val;
    }

?>
<div class="duration-wrap" ref="listsbox">
    <div class="duration-tubes">
        <?php 
        foreach ($lists as $month => $booking) { 
            $month_group = $this->fn->q('time')->month(date("n", strtotime($month)), true);
            $year_group = date("Y", strtotime($month)) + 543;
        ?>
        <div data-id="2017-4">

            <h4 class="duration-wrap-header"><?=$month_group.' '.$year_group?></h4>
            <ul class="ui-list ui-list-duration">
            <?php foreach ($booking as $value) { 

                $year_str = date("Y", strtotime($value['start_date'])) + 543;
                $month_str = $this->fn->q('time')->month(date("n", strtotime($value['start_date'])));
                $day_str = date("d", strtotime($value['start_date']));
            ?>
                <li class="ui-item noCoverImage">
                    <a class="anchor clearfix ui-item-inner" href="#">
                        <div class="avatar-date lfloat mrm"><div><?=$day_str?></div><div class="label"><?=$month_str?></div></div>

                        <div class="content"><div class="spacer"></div><div class="massages">

                        <ul class="disc ui-list-meta">
                            <li>
                                <i class="icon-cube"></i> <label>Package:</label> <strong><?=$value['pack_name']?></strong>
                                , <label>Room:</label> <strong><?=$value['room_name']?></strong>
                            </li>
                            <li>
                                <i class="icon-clock-o"></i> <label>Time:</label> <strong><?=date("H:i", strtotime($value['start_date'])).' - '.date("H:i", strtotime($value['end_date']))?> น.</strong>
                            </li>
                            <li>
                                <i class="icon-user-circle-o"></i> <label>Service By:</label> <i class="icon"></i><strong><?=$value['first_name'].' '.$value['last_name']?> (<?=$value['nickname']?>)</strong>
                            </li>
                        </ul>

                        </div></div>

                    </a>

                    <div class="status-wrap"><a class="ui-status" style="background-color: rgb(219, 21, 6);">Booking</a></div>


                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>

    <a class="ui-more btn" role="more">โหลดเพิ่มเติม</a>
    <div class="ui-alert">
        <div class="ui-alert-loader">
            <div class="ui-alert-loader-icon loader-spin-wrap"><div class="loader-spin"></div></div>
            <div class="ui-alert-loader-text">กำลังโหลด...</div> 
        </div>

        <div class="ui-alert-error">
            <div class="ui-alert-error-icon"><i class="icon-exclamation-triangle"></i></div>
            <div class="ui-alert-error-text">ไม่สามารถเชื่อมต่อได้</div> 
        </div>

        <div class="ui-alert-empty">
            <div class="ui-alert-empty-text">No Result</div> 
        </div>
    </div>
</div>
<?php }else{

    echo '<table class="mtl table-accessory"><tbody><tr><td colspan="3" class="td-empty">No Result</td></tr></tbody></table>';
    } ?>