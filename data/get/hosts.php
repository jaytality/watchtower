<?php

// stores all the hosts being monitored
$data['hosts'] = array();
$data['totals']['hosts'] = 0;
$data['totals']['online'] = 0;
$data['totals']['ingress'] = 0;
$data['totals']['egress'] = 0;

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
		$data['hosts'][$host['id']]['cpuLoad'] = $cpuLoad[0]->lastvalue * 100;

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

        // INGRESS (download)
        $data['hosts'][$host['id']]['ingress'] = 0;
        $ingress = $api->itemGet(array(
            "output" => "extend",
            "hostids" => $host['id'],
            "searchWildcardsEnabled" => true,
            "search" => array(
                "key_" => "net.if.in[*]",
            )
        ));
        foreach($ingress as $in) {
            $data['hosts'][$host['id']]['ingress'] += $in->lastvalue;
        }

        // EGRESS (upload)
        $data['hosts'][$host['id']]['egress'] = 0;
        $egress = $api->itemGet(array(
            "output"                 => "extend",
            "hostids"                => $host['id'],
            "searchWildcardsEnabled" => true,
            "search"                 => array(
                                            "key_" => "net.if.out[*]",
                                        )
        ));
        foreach($egress as $out) {
            $data['hosts'][$host['id']]['egress'] += $out->lastvalue;
        }

        $data['totals']['ingress'] += $data['hosts'][$host['id']]['ingress'];
        $data['totals']['egress']  += $data['hosts'][$host['id']]['egress'];
    }
} catch(Exception $e) {
    echo $e->getMessage();
}
