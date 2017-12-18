<?php

$subname = '';
if( !empty($this->item['phone']) ){
    $subname .= !empty($subname) ? ', ':'';
    $subname .= '<i class="icon-phone mrs"></i>' . $this->item['phone'];
}

if( !empty($this->item['email']) ){
    $subname .= !empty($subname) ? ', ':'';
    $subname .= '<i class="icon-envelope-o mrs"></i>' .$this->item['email'];
}

if( !empty($this->item['lineID']) ){
    $subname .= !empty($subname) ? ', ':'';
    $subname .= 'Line ID: '.$this->item['lineID'].'<a target="_blank" href="http://line.me/ti/p/~'.$this->item['lineID'].'"><i class="mls icon-external-link"></i></a>';
}

$dropdown = array();
/*foreach ($this->status as $key => $value) {

    if( $value['id']==$this->item['status']['id'] ) continue;
    $dropdown[] = array(
        'text' => $value['name'],
        'href' => URL.'booking/update/'.$this->item['id'].'/status/'.$value['id'],
        'attr' => array('data-plugins'=>'dialog'),
    );
}*/

// $dropdown[] = array( 'type' => 'separator');
if( !empty($this->permit['customers']['edit']) || $this->me['id'] == $this->item['emp_id'] ){
    $dropdown[] = array(
        'text' => 'ลบ',
        'href' => URL.'customers/del/'.$this->item['id'],
        'attr' => array('data-plugins'=>'dialog'),
    );

    $dropdown = $this->fn->stringify( array(
        'select' => $dropdown,

        'setttings' => array(
            'axisX' => 'right'
            )
    ) );
    $dropdown = '<a class="btn-icon" data-plugins="dropdown" data-options="'.$dropdown.'"><i class="icon-ellipsis-v"></i></a>';
}
else{
    $dropdown = '';
}

$image = '';
if( !empty($this->item['image_url']) ){
    $image = '<div class="avatar lfloat mrm"><img class="img" src="'.$this->item['image_url'].'" alt="'.$this->item['fullname'].'"></div>';
}
else{
    $image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
}

?><div class="profile-left" role="left" data-width="340">

	<div role="leftHeader">
		
        <div class="clearfix profile-left_header">

            <div class="clearfix profile-left_actions">
                <!-- <div class="lfloat">
                    <a class="btn-icon" href="<?=URL?>customers"><i class="icon-arrow-left"></i></a>
                </div> -->
                <div class="rfloat">
                   <?=$dropdown?>
                </div>
            </div>

            <div class="anchor clearfix">
                <?=$image?>
                <div class="content"><div class="spacer"></div><div class="massages">
                    <h3 class="fullname"><?=$this->item['fullname']?></h3>
                    <div class="subname fsm"><?=$subname?></div>
                </div></div>
            </div>

            <div class="mvs clearfix">

                <div class="fsm fcg"><i class="icon-clock-o mrs"></i>แก้ข้อมูลล่าสุด: <?=$this->fn->q('time')->live( $this->item['updated'] )?>
                    <!-- <div>โดย..</div> -->
                </div>
                
                
            </div>

        </div>
	</div>
	<!-- end: .profile-left-header -->

	<div class="phl" role="leftContent">

        <div class="profile-resume mtm"><?php 
            include 'sections/basic.php';
            include 'sections/contact.php';
            include 'sections/level.php';

        ?></div>

    </div>
    <!-- end: .profile-left-details -->
</div>