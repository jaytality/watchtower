<?php

$settings = array(

	// DEBUGGING
	"environment" => "development",

	// DATABASE SETTINGS
	"db" => array(
		"type" => "mysql",
		"host" => "localhost",
		"name" => "database name",
		"user" => "username",
		"pass" => "password",
	),

	// GLOBAL SITE SETTINGS
	"global" => array(
		"name"     => "WEBSITE NAME",
		"network"  => "NETWORK NAME",
		"url"      => "WEBSITE URL",
		"basedir"  => "SUBDIRECTORY IF NEEDED (NO TRAILING /)",
		"timezone" => "TIMEZONE YOU MIGHT BE IN",
	),

	// MAIL SETTINGS
	"mail" => array(
		"api"  => "",
		"from" => "",
	),

	// ZABBIX SSETTINGS
	"zabbix" => array(
		"url"  => "ZABBIX FRONTEND DIRECTORY / api_jsonrpc.php",
		"user" => "ZABBIX USERNAME",
		"pass" => "ZABBIX PASSWORD",
	),

	"password" => "ACCESS PASSWORD",

);

// determine base directory for routes
$settings['global']['basedir'] = (@$settings['global']['basedir'] != '') ? '/' . $settings['global']['basedir'] : '/';
$baseUrl = $settings['global']['basedir'];
