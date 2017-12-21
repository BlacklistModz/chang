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
				<div ref="table" class="listpage2-table table-mg">
					<table class="table-bordered">
						<thead>
							<tr style="background-color: #3d3d8c; color:white;">
								<th width="15%" style="text-align: center;">Products</th>
								<th width="10%" style="text-align: center;">Fruit</th>
								<th width="10%" style="text-align: center;">Size</th>
								<th width="10%" style="text-align: center;">Brand</th>
								<th width="5%" style="text-align: center;">Plained/Litbo</th>
								<th width="5%" style="text-align: center;">LID</th>
								<th width="10%" style="text-align: center;">Pack Size</th>
								<th width="10%" style="text-align: center;">Weight</th>
								<th width="10%" style="text-align: center;">QTY CARTONS</th>
								<th width="15%" style="text-align: center;">Remark</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->item['items'] as $key => $value) { ?>
							<tr>
								<td class="fwb"><?=$value['type_name']?></td>
								<td class="fwb" style="text-align: center;"><?=$value['pro_code']?></td>
								<td style="text-align: center;"><?=$value['size_name']?></td>
								<td><?=$value['brand_name']?></td>
								<td style="text-align: center;"><?=$value['can_name']?></td>
								<td style="text-align: center;"><?=$value['lid']?></td>
								<td class="fwb" style="text-align: center;"><?=$value['pack']?></td>
								<td class="fwb" style="text-align: center;"><?=$value['weight_dw']?> / <?=$value['weight_nw']?></td>
								<td class="fwb" style="text-align: center;"><?=number_format($value['qty'])?></td>
								<td><?=$value['remark']?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>