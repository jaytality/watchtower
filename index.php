<?php

define('ROOT',dirname(__FILE__));

// error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// benchmarking - page loading
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

// $settings
require(ROOT.'/config.php');
(@include_once(ROOT.'/config.php')) or
die("config.php required, please copy config.default.php to config.php and edit as required");

// load ZabbixApi
require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

// connect to Zabbix API
$api = new ZabbixApi($settings['zabbix']['url'], $settings['zabbix']['user'], $settings['zabbix']['pass']);

// $data for page
$data = array();

// output
include(ROOT.'/views/header.php');

// home ajax script
?>

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
		</div>
		<div id="hosts" class="col-sm-10">
			<?php
			// OUTPUT
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
								<td>
									<?=$host['uptime']?>
								</td>
								<td class="text-right">
									<?php
										if(isset($host['os'])) {
											switch($host['os']) {
												case "Windows":
													echo "WIN";
												break;

												case "Linux":
													echo "LIN";
												break;

												case "Darwin":
													echo "MAC";
												break;
											}
										}
									?>
									<?=$host['status'] == true ? '<span style="color: #00ff00; ">ONLINE</span>' : '<span style="color: #ff0000; ">OFFLINE</span>'?>
								</td>
							</tr>
						</table>
						<div class="clearfix"></div>
						<div class="row" style="font-size: 1.2rem; ">
							<!-- CPU -->
							<div class="col-xs-4 text-center">
								<span style="font-size: 1.6rem;">CPU</span><br />
								<?=$host['cpuLoad']. ' %'?>
							</div>

							<!-- MEMORY -->
							<div class="col-xs-4 text-center">
								<span style="font-size: 1.6rem;">RAM</span><br />
								<?=$host['memory']['free'].' / '.$host['memory']['total']?>
							</div>

							<!-- DISK -->
							<div class="col-xs-4 text-center">
								<span style="font-size: 1.6rem;">HDD</span><br />
								<?=$host['diskUsage'].' / '.$host['diskTotal']?>
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

<script type="text/javascript">
  function updateHosts(){
    $('#hosts').load('data/home.php');
  }
  setInterval( "updateHosts()", 1000 );
</script>
<?php

include(ROOT.'/views/footer.php');

// clearing $data
$data = array();
