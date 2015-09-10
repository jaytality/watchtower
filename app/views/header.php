<?php

// common stuff
include(ROOT.'/app/common.php');

// benchmarking - page loading
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>
		<?= isset($data['page']['title']) ? $data['page']['title'] .' - '. $settings['global']['name'] : 'Home | '. $settings['global']['name'] ?>
	</title>

	<link href='https://fonts.googleapis.com/css?family=Dosis:400,300,700,500' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

	<!-- watchtower javascript -->
	<script src="<?=$home . 'assets/js/watchtower.js'?>?<?=uniqid()?>"></script>

	<!-- watchtower css -->
	<link rel="stylesheet" href="<?=$home . 'assets/css/theme.css'?>?<?=uniqid()?>">
	<link rel="stylesheet" href="<?=$home . 'assets/css/watchtower.css'?>?<?=uniqid()?>">

</head>

<body>

<!-- content -->
