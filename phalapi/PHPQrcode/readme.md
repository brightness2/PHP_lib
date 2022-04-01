# QRCode 二维码生成

## 注册
init.php 文件中添加
```
//QRcode
$di->qrcode = new PHPQrcode_Lib();
```

## 使用
第一种使用方式：直接输出二维码图片：
```
 DI()->qrcode->png('Hello PhalApi!', false, 'L', 4);
 exit;
```
第二种使用方式：将二维码图片保存到文件。
```
DI()->qrcode->png('Hello PhalApi!', '/path/to/your_file.png', 'L', 4);
```