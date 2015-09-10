<?php
// PROJECT: Watchtwoer
// URL: watchtower.jtiong.com
// jtiong@jtiong.com

session_start();

// critical, required files!
define('ROOT',dirname(__FILE__));

require(ROOT.'/app/config.php');
(@include_once(ROOT.'/app/config.php')) or die("app/config.php required, please copy app/config.default.php to app/config.php and edit as required");

// alternate globals
define('controllers', ROOT.'/app/controllers');
define('models', ROOT.'/app/models');
define('views', ROOT.'/app/views');

// CORE LIBRARIES
require_once(ROOT.'/core/baseline.php');
require_once(ROOT.'/core/rb.php');

ini_set('log_errors', 1);

// error logging
if($settings['environment'] == 'development') {
	error_reporting(E_ALL); // comment out to stop error reporting to browser, notices are not reported!
	ini_set('display_errors', 1);
} else {
	error_reporting(E_ALL & ~E_NOTICE); // comment out to stop error reporting to browser, notices are not reported!
	ini_set('display_errors', 0);
}

// timezone
date_default_timezone_set($settings['global']['timezone']);

// RedBeanPHP
R::setup($settings['db']['type'].':host='.$settings['db']['host'].';dbname='.$settings['db']['name'], $settings['db']['user'], $settings['db']['pass']);
R::ext('xdispense', function( $type ){ return R::getRedBean()->dispense( $type ); }); // allow _ in table names etc.

// SETUP Baseline core
$base = new Baseline();

// MODELS
foreach (glob(ROOT."/app/models/*.php") as $filename) { require_once $filename; }

// Routing
include(ROOT.'/routes/routes.php');

R::close();
