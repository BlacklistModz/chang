<?php
$form = new Form();
$form = $form ->create()
			  ->elem('div')
			  ->addClass('form-insert form-packing');



$options = $this->fn->stringify( array(
		'items' => !empty($this->item['items']) ? $this->item['items'] : array(),
) );
?>



<div id="mainContainer" class="report-main clearfix" data-plugins="main">
	<div role="content">
		<div role="main" class="pal">
			<form class="js-submit-form" action="<?=URL?>packing/save" data-plugins="packingForm" data-options="<?=$options?>">
				<h3 class="fwb mbm"><i class="icon-cube"></i> PACKING</h3>

				<div class="uiBoxWhite pas pam" style="width:900px;">
					<div class="clearfix">

								<h3 class="mbm fwb"><i class="icon-archive"></i> PACKING DETAIL</h3>
								<ul>
									<li>
										<label><span class="fwb">DATE : </span><?=date("d/m/Y", strtotime($this->planload['date']))?></label>
									</li>
									<li>
										<label><span class="fwb">ซานชลา : </span><?=$this->planload['platform_id']?></label>
									</li>
									<li>
										<label><span class="fwb">BRAND : </span><?=$this->planload['brand_id']?></label>
									</li>
									<li>
										<label><span class="fwb">Product : </span><?=$this->planload['type_id']?></label>
									</li>
									<li>
										<label>
											<span class="fwb">Fruit : </span><?=$this->planload['pro_id']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">Grade : </span><?=$this->planload['grade_id']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">Size : </span><?=$this->planload['size_id']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">Weight : </span><?=$this->planload['weight_id']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">FCL : </span><?=$this->planload['fcl']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">Cartons : </span><?=$this->planload['carton']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">JO NO. : </span><?=$this->planload['job_id']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">INV NO. : </span><?=$this->planload['inv']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">คืนตู้ : </span><?=$this->planload['cabinet_return']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">CLOSED : </span><?=$this->planload['closed_date']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">รับตู้ : </span><?=$this->planload['cabinet_get']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">ETD : </span><?=$this->planload['etd_date']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">SHIP : </span><?=$this->planload['ship']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">SHIPPER : </span><?=$this->planload['shipper']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">REMARK : </span><?=$this->planload['remark']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">ขออนุมัติส่งออก : </span><?=$this->planload['approval']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">บรรจุภัณฑ์ (กล่อง) : </span><?=$this->planload['package_carton']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">บรรจุภัณฑ์ (ฉลาก) : </span><?=$this->planload['package_label']?>
										</label>
									</li>
									<li>
										<label>
											<span class="fwb">CARTON REMARK : </span><?=$this->planload['carton_remark']?>
										</label>
									</li>
								</ul>
					</div>
				</div>



				<div class="uiBoxWhite pas pam" style="width:900px;">
					<?=$form->html()?>
					<div class="lists-items" role="listsitems">
						<table class="lists-items-listbox table-bordered">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="45%">PALLET/JO NO.</th>
									<th width="12%" style="text-align: center;">จำนวน</th>
									<th width="12%" style="text-align: center;">บุบ</th>
									<th width="12%" style="text-align: center;">เสียหาย</th>
									<th width="12%" style="text-align: center;">จัดการ</th>
								</tr>
							</thead>
							<tbody role="listsitem"></tbody>
						</table>
					</div>
				</div>
				<div class="uiBoxWhite pas pam clearfix"  style="width:900px;">
					<a href="<?=URL?>packing" class="btn btn-red lfloat">กลับ</a>
					<button type="submit" class="btn btn-blue btn-submit rfloat">บันทึก</button>
				</div>
			</form>
		</div>
	</div>
</div>
