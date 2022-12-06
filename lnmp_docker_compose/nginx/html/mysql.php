<?php
// host 参数是容器名称，可以通过 docker network inspect lnmp_lnmp 查看同网络的容器，不同网络的不能连接
$link = mysqli_connect('mysql5.7',"root",'root','test');
var_dump($link);