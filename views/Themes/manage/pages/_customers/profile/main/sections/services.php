<?php if( !empty($this->services) ){ 

    $lists = array();
    foreach ($this->services as $key => $val) {
        $date = date("Y-m-d", strtotime($val['start_date']));
        $lists[$date][] = $val;
    }

?>
<div class="timeline">
    <?php foreach ($lists as $date => $service) { ?>
    <div class="item">
        <div class="date"><?=$this->fn->q('time')->normal($date)?></div>
        <ul class="inner">
            <?php foreach ($service as $value) { ?>
            <li>
                <div class="time"><?=date("H:i", strtotime($value['start_date'])).' - '.date("H:i", strtotime($value['end_date']))?> น.</div>
                <ul class="disc ui-list-meta">
                    <li>
                        <i class="icon-cube"></i> <label>Package:</label> <strong><?=$value['pack_name']?></strong>
                        , <label>Room:</label> <strong><?=$value['room_name']?></strong>
                    </li>
                    <li>
                        <i class="icon-user-circle-o"></i> <label>Service By:</label> <i class="icon"></i><strong><?=$value['first_name'].' '.$value['last_name']?> (<?=$value['nickname']?>)</strong>
                    </li>

                    <li>
                        <i class="icon-money"></i> <label>Total Price:</label> <strong>฿<span><?=($value['item_price'] - $value['item_discount'])?></span></strong>
                    </li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>
<?php }else{
    echo '<table class="mtl table-accessory"><tbody><tr><td colspan="3" class="td-empty">No Result</td></tr></tbody></table>';
    } 
?>