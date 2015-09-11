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

include(ROOT.'/data/home.php');
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
			<div class="clearfix"></div>
			<!-- ingress (downloads) -->
			<span style="color: #00ff00; font-size: 1.6rem; float: left; ">
				<?=$data['totals']['ingress']?> <i class="icon-down-dir"></i>
			</span>
			<!-- egress (uploads) -->
			<span style="color: #ff0000; font-size: 1.6rem; float: right; text-align: right; ">
				<?=$data['totals']['egress']?> <i class="icon-up-dir"></i>
			</span>
		</div>
		<div id="hosts" class="col-sm-10">
			<?php include(ROOT.'/views/hosts.php'); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
  function updateHosts(){
    $('#hosts').load('data/hosts.php');
  }
  setInterval( "updateHosts()", 10000 );
</script>
<?php

include(ROOT.'/views/footer.php');

// clearing $data
$data = array();
