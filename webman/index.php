<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./" method="post">
        <input type="text" name="name">

        <button type="submit">提交任务</button>
    </form>
</body>
</html>

<?php
function redis_queue_send($redis, $queue, $data, $delay = 0) {
    $queue_waiting = '{redis-queue}-waiting'; //1.0.5版本之前为redis-queue-waiting
    $queue_delay = '{redis-queue}-delayed';//1.0.5版本之前为redis-queue-delayed

    $now = time();
    $package_str = json_encode([
        'id'       => rand(),
        'time'     => $now,
        'delay'    => 0,
        'attempts' => 0,
        'queue'    => $queue,
        'data'     => $data
    ]);
    if ($delay) {
        return $redis->zAdd($queue_delay, $now + $delay, $package_str);
    }
    return $redis->lPush($queue_waiting.$queue, $package_str);
}


$name = $_POST['name'];
if($name){
    $redis = new Redis;//参数$redis为redis实例。例如redis扩展
    $bool = $redis->connect('127.0.0.1','6379');
    if($bool){
        $queue = 'user-1';
        $data= ['some', $name];
        redis_queue_send($redis,$queue,$data);
    }
    
}
?>