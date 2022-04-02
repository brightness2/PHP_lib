# PHPMailer 扩展
基于PHPMailer 进行的扩展

## 配置
config/app.php 文件添加
```php
'PHPMailer' => array(
        'email' => array(
            'host' => 'smtp.gmail.com',
            'port' => 465,
            'Secure' => 'ssl',
            'username' => 'XXX@gmail.com',
            'password' => '******',
            'from' => 'XXX@gmail.com',
            'fromName' => 'PhalApi团队',
            'sign' => '<br/><br/>请不要回复此邮件，谢谢！<br/><br/>-- PhalApi团队敬上 ',
        ),
    ),
```

## 注册
init.php 文件添加
```php
DI()->mailer =new PHPMailer_Lite();
```