# cors 跨域扩展


## init.php 文件中添加
```
DI()->cors = new \PhalApi\CORS\Lite();
```
## config/app.php 添加配置
```
//cors跨域配置
'cors' => array(
    //域名白名单
    'whitelist'   => array(
        //'http://xxx.xx.xxx',
        //'http://xxx.xxx.xxx'
    ),  
    //header头
    'headers' => array(
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS', //支持的请求类型
        'Access-Control-Allow-Credentials' => 'true' //支持cookie
    )
)
```
