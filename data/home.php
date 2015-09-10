<?php

error_reporting(E_ALL); // comment out to stop error reporting to browser, notices are not reported!
ini_set('display_errors', 1);

define('ROOT',dirname(__FILE__));
require_once('../config.php');

// load ZabbixApi
require_once 'lib/ZabbixApi.class.php';
use ZabbixApi\ZabbixApi;

// connect to Zabbix API
$api = new ZabbixApi($settings['zabbix']['url'], $settings['zabbix']['user'], $settings['zabbix']['pass']);

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a D %h H %i M %s S');
}

function bytesToSize($bytes, $precision = 2)
{
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;

    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';

    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';

    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';

    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';

    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

// stores all the hosts being monitored
$data['hosts'] = array();
$data['totals']['hosts'] = 0;
$data['totals']['online'] = 0;

try {
    // retrieve all hosts
    $hosts = $api->hostGet(array(
    	'output' => [ 'host' ]
    ));

    foreach($hosts as $host) {
    	$data['hosts'][$host->hostid] = [
			'id'   => $host->hostid,
			'name' => $host->host
    	];

        $data['totals']['hosts']++;
    }

    foreach($data['hosts'] as $host) {
        // OPERATING SYSTEM
    	$osystem = $api->itemGet(array(
			"output"  => "extend",
			"hostids" => $host['id'],
			"search"  => array(
				"key_" => "system.uname",
			)
		));
        $osystem = explode(" ", $osystem[0]->lastvalue);
        $data['hosts'][$host['id']]['os'] = $osystem[0];

        // IP ADDRESS
        $hostIPs = $api->hostGet(array(
        	"output"           => "extend",
        	"hostids"          => $host['id'],
        	"selectInterfaces" => [ "ip" ]
        ));
        foreach($hostIPs as $hostIP) {
        	$data['hosts'][$host['id']]['address'] = $hostIP->interfaces[0]->ip;
        }

        // UPTIME
    	$uptime = $api->itemGet(array(
			"output"  => "extend",
			"hostids" => $host['id'],
			"search"  => array(
				"key_" => "system.uptime",
			)
		));
        $data['hosts'][$host['id']]['uptime'] = secondsToTime($uptime[0]->lastvalue);

        // ONLINE STATUS
    	$status = $api->itemGet(array(
			"output"  => "extend",
			"hostids" => $host['id'],
			"search"  => array(
				"key_" => "agent.ping",
			)
		));
        if($status[0]->lastvalue == 1) {
            $data['hosts'][$host['id']]['status'] = true;
            $data['totals']['online']++;
        } else {
            $data['hosts'][$host['id']]['status'] = false;
        }

        // CPU LOAD
		$cpuLoad = $api->itemGet(array(
			"output" => "extend",
			"hostids" => $host['id'],
			"search" => array(
				"key_" => "system.cpu.load[percpu,avg1]",
			)
		));
		$data['hosts'][$host['id']]['cpuLoad'] = $cpuLoad[0]->lastvalue;

        // DISK USED
        $data['hosts'][$host['id']]['diskUsage'] = 0;
        $disks = $api->itemGet(array(
            "output" => "extend",
            "hostids" => $host['id'],
            "searchWildcardsEnabled" => true,
            "search" => array(
                "key_" => "vfs.fs.size[*,used]",
            )
        ));
        foreach($disks as $disk) {
            $data['hosts'][$host['id']]['diskUsage'] += $disk->lastvalue;
        }
        $data['hosts'][$host['id']]['diskUsage'] = bytesToSize($data['hosts'][$host['id']]['diskUsage']);

        // DISK TOTAL
        $data['hosts'][$host['id']]['diskTotal'] = 0;
        $disks = [];    // clear from diskUsage
        $disks = $api->itemGet(array(
            "output" => "extend",
            "hostids" => $host['id'],
            "searchWildcardsEnabled" => true,
            "search" => array(
                "key_" => "vfs.fs.size[*,total]",
            )
        ));
        foreach($disks as $disk) {
            $data['hosts'][$host['id']]['diskTotal'] += $disk->lastvalue;
        }
        $data['hosts'][$host['id']]['diskTotal'] = bytesToSize($data['hosts'][$host['id']]['diskTotal']);

        // AVAILABLE MEMORY
    	$memory = $api->itemGet(array(
			"output"  => "extend",
			"hostids" => $host['id'],
			"search"  => array(
				"key_" => "vm.memory.size[available]",
			)
		));
		$data['hosts'][$host['id']]['memory']['free'] = isset($memory[0]->lastvalue) ? bytesToSize($memory[0]->lastvalue) : 'n/a';

        // TOTAL MEMORY
        $memory = [];
    	$memory = $api->itemGet(array(
			"output"  => "extend",
			"hostids" => $host['id'],
			"search"  => array(
				"key_" => "vm.memory.size[total]",
			)
		));
		$data['hosts'][$host['id']]['memory']['total'] = isset($memory[0]->lastvalue) ? bytesToSize($memory[0]->lastvalue) : 'n/a';
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

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
