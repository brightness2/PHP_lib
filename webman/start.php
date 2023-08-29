<?php
use Workerman\Worker;
use Workerman\Timer;
use Workerman\RedisQueue\Client;
require_once __DIR__ . '/vendor/autoload.php';

$worker = new Worker();
$worker->onWorkerStart = function () {
    $client = new Client('redis://127.0.0.1:6379');
   // 订阅
    $client->subscribe('user-1', function($data){
        echo "user-1\n";
        var_export($data);
    });
   // 订阅
    $client->subscribe('user-2', function($data){
        echo "user-2\n";
        var_export($data);
    });
    // 定时向队列发送消息
    // Timer::add(1, function()use($client){
    //     $client->send('user-1', ['some', 'data']);
    // });
};

Worker::runAll();