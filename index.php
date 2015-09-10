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

// $data for page
$data = array();

// output
include(ROOT.'/views/header.php');

include(ROOT.'/data/home.php');
include(ROOT.'/views/home.php');

// home ajax script
?>
<script type="text/javascript">
  function updateHosts(){
    $('#hosts').load('data/home.php');
  }
  setInterval( "updateHosts()", 10000 );
</script>
<?php

include(ROOT.'/views/footer.php');

// clearing $data
$data = array();
