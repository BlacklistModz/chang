<div id="mainContainer" class="profile clearfix" data-plugins="main">
	<div class="setting-content" role="content">
		<div class="setting-main" role="main">
			<div class="pas pam">
				<div class="clearfix mbm">
					<div class="span11">
						<div class="lfloat">
							<div class="setting-title">
								<ul calss="lfloat" ref="action"><li class="mt">

								<?php
								// print_r($this->item['type_id']);die;
								if($this->item['type_id'] == 1)$icon = 'rambutan';
								elseif($this->item['type_id'] == 3)$icon = 'creamcorn';
								elseif($this->item['type_id'] == 4)$icon = 'longan';
								elseif($this->item['type_id'] == 5)$icon = 'lychee';
								elseif($this->item['type_id'] == 6)$icon = 'mango';
								elseif($this->item['type_id'] == 8)$icon = 'corncup';
								elseif($this->item['type_id'] == 9)$icon = 'bakedlongan';
								else $icon = 'corn';
									?>
								<i class="icon-<?=$icon?> _ico-center"></i> Pallet Code : <?=$this->item['code']?> (<?=$this->item['delivery_code']?>)</div>

								</li>
							</ul>
						</div>
						<div class="rfloat">
							<?php if( $this->item['qty'] > 0 ) { ?>
							<a href="<?=URL?>pallets/add_check/<?=$this->item['id']?>" class="btn btn-red" data-plugins="dialog"><i class="icon-hand-lizard-o"></i> ดึงตรวจสินค้า</a>
							<?php } ?>
							<a href="<?=URL?>pallets/setFraction/<?=$this->item['id']?>" class="btn btn-orange" data-plugins="dialog"><i class="icon-plus"></i> รวมเศษ</a>
							<a href="<?=URL?>hold/add/<?=$this->item['id']?>" class="btn btn-blue" data-plugins="dialog"><i class="icon-plus"></i> เพิ่มรายการ Hold</a>
						</div>
					</div>
				</div>

				<div class="clearfix">

					<div class="span9">
						<div class="uiBoxWhite pam">
							<h3 class="mbm fwb"><i class="icon-cube"></i> ข้อมูลพาเลท</h3>
							<ul>
								<li>
									<label><span class="fwb">Pallet Code : </span><?=$this->item['code']?></label>
								</li>
								<li>
									<label><span class="fwb">Delivery Code : </span><?=$this->item['delivery_code']?></label>
								</li>
								<li>
									<label><span class="fwb">วันที่ผลิต : </span><?=date("d/m/Y", strtotime($this->item['date']))?></label>
								</li>
								<li>
									<label><span class="fwb">RETORT : </span></label>
									<?php 
									$retort = '';
									foreach ($this->item['retort'] as $key => $value) {
										$retort .= !empty($retort) ? " , " : "";
										$retort .= $value['rt_name'].'/'.$value['batch'].' : <span class="fwb">'.$value['qty'].'</span>';
									}
									echo $retort;
									?>
								</li>
							</ul>
						</div>
					</div>
					<div class="span2">
						<div class="uiBoxWhite tac">
							<h3 class="fwb mtm"><i class="icon-database"></i> กระป๋องทั้งหมด</h3>
							<span style="font-size: 62px;"><?=number_format($this->item['qty'])?></span>
						</div>
					</div>

				</div>

				<div class="clearfix">

					<div class="span11 mtm">
						<div class="uiBoxWhite pam">
							<h3 class="mbm fwb"><i class="icon-list-alt"></i> ประวัติการ Hold</h3>
							<div ref="table" class="listpage2-table">
								<?php if( !empty($this->hold['lists']) ) { ?>
								<table class="table-bordered">
									<thead>
										<tr>
											<th class="ID">ครั้งที่</th>
											<th class="date" style="text-align: center;">วันที่ Hold</th>
											<th class="date" style="text-align: center;">กำหนดปล่อย</th>
											<th class="qty" style="text-align: center;">จำนวน</th>
											<th class="contact">สาเหตุ</th>
											<th class="contact">หมายเหตุ</th>
											<th class="status">สถานะ</th>
											<th class="actions">จัดการ</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no=0;
										foreach ($this->hold['lists'] as $key => $value) {
											$no++;
											$cls = $value['status']['id'] == 1 ? "" : "disabled";

											$cause = '';
											if( !empty($value['cause']) ){
												foreach ($value['cause'] as $val) {
													$cause .= '<p>- '.$val['name'].' '.$val['note'].'</p>';
												}
											}
											?>
										<tr>
											<td class="ID"><?=$no?></td>
											<td class="date" style="text-align: center;">
												<?=date("d/m/Y", strtotime($value['start_date']))?>
											</td>
											<td class="date" style="text-align: center;">
												<?=date("d/m/Y", strtotime($value['end_date']))?>
											</td>
											<td class="qty" style="text-align: center;">
												<?=$value['qty']?>
											</td>
											<td class="contact"><?= !empty($cause) ? $cause : "-" ?></td>
											<td class="contact"><?= !empty($value['note']) ? nl2br($value['note']) : '-'?></td>
											<td class="status"><?=$value['status']['name']?></td>
											<td class="actions whitespace">
												<span class="gbtn">
													<a href="<?=URL?>hold/set_hold/<?=$value['id']?>" data-plugins="dialog" class="btn btn-blue btn-no-padding <?=$cls?>"><i class="icon-check"></i></a>
												</span>
												<span class="gbtn">
													<a href="<?=URL?>hold/edit/<?=$value['id']?>" data-plugins="dialog" class="btn btn-orange btn-no-padding <?=$cls?>"><i class="icon-pencil"></i></a>
												</span>
												<span class="gbtn">
													<a href="<?=URL?>hold/del/<?=$value['id']?>" data-plugins="dialog" class="btn btn-red btn-no-padding <?=$cls?>"><i class="icon-trash"></i></a>
												</span>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php }
								else{
									echo '<div class="tac"><h3 class="fwb">ไม่พบประวัติการ Hold</h3></div>';
								} ?>
							</div>
						</div>
					</div>

				</div>

				<div class="clearfix">

					<div class="span11 mtm">
						<div class="uiBoxWhite pam">
							<h3 class="mbm fwb"><i class="icon-list-alt"></i> ประวัติการดึงตรวจ</h3>
							<div ref="table" class="listpage2-table">
								<?php if( !empty($this->item['checks']) ) { ?>
								<table class="table-bordered">
									<thead>
										<tr>
											<th width="15%">ลำดับ</th>
											<th width="15%">วันที่</th>
											<th width="15%">จำนวน</th>
											<th width="55%">สาเหตุ</th>
										</tr>
									</thead>
									<tbody>
										<?php $num=0; foreach ($this->item['checks'] as $key => $value) { $num++; ?>
										<tr>
											<td style="text-align: center;"><?=$num?></td>
											<td style="text-align: center;"><?=date("d/m/Y", strtotime($value['created']))?></td>
											<td style="text-align: center;"><?=$value['qty']?></td>
											<td><?=nl2br($value['remark'])?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php }
								else{
									echo '<div class="tac"><h3 class="fwb">ไม่พบประวัติการดึงตรวจ</h3></div>';
								} ?>
							</div>
						</div>
					</div>
				</div>

				<div class="clearfix">
					<div class="span11 mtm">
						<div class="uiBoxWhite pam">
							<h3 class="mbm fwb"><i class="icon-list-alt"></i> ประวัติการรวมเศษ</h3>
							<div ref="table" class="listpage2-table">
								<?php if( !empty($this->item['fraction']) ) { ?>
								<table class="table-bordered">
									<thead>
										<tr>
											<th width="30%">DATE</th>
											<th width="30%">PALLET NO.</th>
											<th width="30%">จำนวน</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($this->item['fraction'] as $key => $value) { ?>
										<tr>
											<td class="fwb" style="text-align: center;"><?=date("d/m/Y", strtotime($value['date']))?></td>
											<td class="fwb" style="text-align: center;"><?=$value['old_pallet_code']?></td>
											<td class="fwb" style="text-align: center;"><?=$value['qty']?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php }
								else{
									echo '<div class="tac"><h3 class="fwb">ไม่พบประวัติการรวมเศษ</h3></div>';
								} ?>
							</div>
						</div>
					</div>
				</div>

			</div>

			</div>
		</div>
	</div>
</div>
