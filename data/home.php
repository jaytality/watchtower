<?php

error_reporting(E_ALL); // comment out to stop error reporting to browser, notices are not reported!
ini_set('display_errors', 1);

require_once('./config.php');

// load ZabbixApi
require_once('./lib/ZabbixApi.class.php');
use ZabbixApi\ZabbixApi;

// connect to Zabbix API
$api = new ZabbixApi($settings['zabbix']['url'], $settings['zabbix']['user'], $settings['zabbix']['pass']);

// unit conversions
include_once('./lib/conversion.php');

include_once('./data/get/hosts.php');