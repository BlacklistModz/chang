<div id="mainContainer" class="profile clearfix" data-plugins="main">
	<div class="setting-content" role="content">
		<div class="setting-main" role="main">
			<div class="pas pam">
				<div class="clearfix mbm">
					<div class="span11">
						<div class="lfloat">
							<div class="setting-title"><i class="icon-cube"></i> Pallet Code : <?=$this->item['code']?> (<?=$this->item['delivery_code']?>)</div>
						</div>
						<div class="rfloat">
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

			</div>

			</div>
		</div>
	</div>
</div>