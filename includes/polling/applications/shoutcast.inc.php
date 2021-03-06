<?php

// Polls shoutcast statistics from script via SNMP

use LibreNMS\RRD\RrdDefinition;

$name = 'shoutcast';
$app_id = $app['app_id'];

$options = '-O qv';
$oid     = 'nsExtendOutputFull.9.115.104.111.117.116.99.97.115.116';
$shoutcast = snmp_get($device, $oid, $options);

echo ' shoutcast';

$servers = explode("\n", $shoutcast);

foreach ($servers as $item => $server) {
    $server = trim($server);

    if (!empty($server)) {
        $data = explode(';', $server);
        list($host, $port) = explode(':', $data['0'], 2);

        $rrd_name = array('app', $name, $app_id, $host . '_' . $port);
        $rrd_def = RrdDefinition::make()
            ->addDataset('bitrate', 'GAUGE', 0, 125000000000)
            ->addDataset('traf_in', 'GAUGE', 0, 125000000000)
            ->addDataset('traf_out', 'GAUGE', 0, 125000000000)
            ->addDataset('current', 'GAUGE', 0, 125000000000)
            ->addDataset('status', 'GAUGE', 0, 125000000000)
            ->addDataset('peak', 'GAUGE', 0, 125000000000)
            ->addDataset('max', 'GAUGE', 0, 125000000000)
            ->addDataset('unique', 'GAUGE', 0, 125000000000);

        $fields = array(
            'bitrate'  => $data['1'],
            'traf_in'  => $data['2'],
            'traf_out' => $data['3'],
            'current'  => $data['4'],
            'status'   => $data['5'],
            'peak'     => $data['6'],
            'max'      => $data['7'],
            'unique'   => $data['8'],
        );

        $tags = compact('name', 'app_id', 'host', 'port', 'rrd_name', 'rrd_def');
        data_update($device, 'app', $tags, $fields);
    }//end if
}//end foreach
