<?php

include 'include/lib/log.php';

$map = array();//客户端集合

$server = new swoole_websocket_server("0.0.0.0", 9501);

$server->set([
	'worker_num' => 2,
	'reactor_num'=>8,
	'task_worker_num'=>1,
	'dispatch_mode' => 2,
	'debug_mode'=> 1,
	'daemonize' => true,
	'log_file' => __DIR__.'/webs_swoole.log',
	'heartbeat_check_interval' => 60,
	'heartbeat_idle_time' => 600,
]);
$server->on('open', function (swoole_websocket_server $server, $request) {
    global $map;//客户端集合
    $map[$request->fd] = $request->fd;//首次连上时存起来
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
	
    global $map;//客户端集合
    global $server;
    
    _log(json_encode($server->connections),'wesocket-log');
    
    $data = $frame->data;
    foreach($map as $fd){
        $server->push($fd , $data);//循环广播
    }
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();