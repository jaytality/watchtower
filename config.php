<?php

$settings = array(

	// DEBUGGING
	"environment" => "development",

	// DATABASE SETTINGS
	"db" => array(
		"type" => "mysql",
		"host" => "localhost",
		"name" => "zabbix",
		"user" => "root",
		"pass" => "M4t4nt31@a",
	),

	// GLOBAL SITE SETTINGS
	"global" => array(
		"name"     => "Watchtower",
    "network"  => "Jtiong Network",
		"url"      => "",
		"basedir"  => "",
		"timezone" => "Australia/Sydney",
	),

	// MAIL SETTINGS
	"mail" => array(
		"api"  => "",
		"from" => "",
	),

	// ZABBIX SSETTINGS
	"zabbix" => array(
		"url"  => "http://watchtower.jtiong.com/zabbix/api_jsonrpc.php",
		"user" => "admin",
		"pass" => "M4t4nt31@a",
	),

);

// determine base directory for routes
$settings['global']['basedir'] = (@$settings['global']['basedir'] != '') ? '/' . $settings['global']['basedir'] : '/';
$baseUrl = $settings['global']['basedir'];
