<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<div class="uiBoxWhite pam pas">
				<h3>
					<i class="icon-clipboard"></i>
					<span class="fwb">JOB ORDER : </span><?=$this->item['code']?>
				</h3>
				<ul class="mtm">
					<li><span class="fwb">DATE</span> <?=date("d/m/y", strtotime($this->item['date']))?></li>
					<li><span class="fwb">BUYER</span> <?=$this->item['cus_name']?></li>
				</ul>
			</div>

			<div class="uiBoxWhite pam pas mtm">
				<h3><i class="icon-list"></i></h3>
				
			</div>
		</div>
	</div>
</div>