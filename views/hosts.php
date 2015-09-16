<div id="hosts">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2">
				<h1><?=$settings['global']['name']?></h1>
				<p>
					<?=$settings['global']['network']?>
				</p>
				<hr />
				<span style="font-size: 2rem; float: left; ">ONLINE</span>
				<span style="font-size: 2rem; float: right; text-align: right; ">
					<?=$data['totals']['online']?> /
					<span style="color: #ccc; "><?=$data['totals']['hosts']?></span>
				</span>
				<div class="clearfix"></div>
				<hr />
				<!-- ingress (downloads) -->
				<span style="color: #00ff00; font-size: 1.6rem; float: left; ">
					<?=bytesToSize($data['totals']['ingress']).'ps'?> <i class="icon-down-dir"></i>
				</span>
				<!-- egress (uploads) -->
				<span style="color: #ff0000; font-size: 1.6rem; float: right; text-align: right; ">
					<i class="icon-up-dir"></i> <?=bytesToSize($data['totals']['egress']).'ps'?>
				</span>
				<div class="clearfix"></div>
				<hr />
				<a href="<?=$baseUrl?>?action=logout">Log Out</a>
			</div>
			<div class="col-sm-10">
				<?php
				$count = 0;
				foreach($data['hosts'] as $host) {
					?>
					<div class="col-sm-4">
						<div class="well host">
							<table class="table table-borderless" style="border-bottom: 1px dashed cyan; ">
								<tr>
									<th>
										NAME
									</th>
									<td>
										<?=$host['name']?>
									</td>
									<td class="text-right">
										<?=$host['address'] == '127.0.0.1' ? '45.32.241.7' : $host['address']?>
									</td>
								</tr>
								<tr>
									<th>
										UPTIME
									</th>
									<td style="text-transform: none; ">
										<?=$host['uptime']?>
									</td>
									<td class="text-right">
										<?php
										if(isset($host['os'])) {
											switch($host['os']) {
												case "Windows":
												?>
												<i class="icon-windows"></i>
												<?php
												break;

												case "Linux":
												?>
												<i class="icon-linux"></i>
												<?php
												break;

												case "Darwin":
												?>
												<i class="icon-appstore"></i>
												<?php
												break;
											}
										}
										?>
										<?=$host['status'] == true ? '<span style="color: #00ff00; ">ONLINE</span>' : '<span style="color: #ff0000; ">OFFLINE</span>'?>
									</td>
								</tr>
							</table>
							<div class="clearfix"></div>
							<div class="row" style="margin-bottom: 1.5rem; ">
								<!-- ingress (downloads) -->
								<div class="col-sm-6">
									<span style="color: #00ff00; ">
									<?=bytesToSize($host['ingress']).'ps'?> <i class="icon-down-dir"></i>
									</span>
								</div>

								<!-- egress (uploads) -->
								<div class="col-sm-6 text-right">
									<span style="color: #ff0000; ">
									<i class="icon-up-dir"></i> <?=bytesToSize($host['egress']).'ps'?>
									</span>
								</div>
							</div>
							<hr />
							<div class="row" style="font-size: 1.2rem; ">
								<!-- CPU -->
								<div class="col-xs-4 text-center">
									<span style="font-size: 1.6rem;">USED CPU</span><br />
									<?=$host['cpuLoad']. ' %'?>
								</div>

								<!-- MEMORY -->
								<div class="col-xs-4 text-center">
									<span style="font-size: 1.6rem;">FREE RAM</span><br />
									<?=$host['memory']['free'].' <span style="color: #777; ">'.$host['memory']['total'].'</span>'?>
								</div>

								<!-- DISK -->
								<div class="col-xs-4 text-center">
									<span style="font-size: 1.6rem;">USED HDD</span><br />
									<?=$host['diskUsage'].' <span style="color: #777; ">'.$host['diskTotal'].'</span>'?>
								</div>
							</div>
						</div>
					</div>
					<?php
					$count++;
					if($count >= 3) {
						echo '<div class="clearfix"></div>';
						$count = 0;
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
