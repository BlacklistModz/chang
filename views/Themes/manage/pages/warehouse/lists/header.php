<?php
include "tablelists.php";
?>
<div ref="header" class="listpage2-header clearfix">
	<div ref="actions" class="listpage2-actions">
		<div id="mainContainer" class="profile clearfix" data-plugins="main">
			<div class="setting-content" role="content">
				<div class="setting-main" role="main">

					<div class="pas pam">
						<div class="clearfix mbm">
							<div class="span11">
								<div class="lfloat">
									<div class="setting-title"><i class="icon-home"></i> Zone : <?=$this->item['name']?></div>
								</div>
							</div>
							<div class="clearfix">

								<div class="span10">
									<div class="uiBoxWhite pam">
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
					</div>
				</div>
			</div>
		</div>
	</div>
