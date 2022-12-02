# 图片访问，隐藏真实路径

读取图片文件，并以字节流输出

## 使用
在public文件夹增加file.php文件
```
defined('API_ROOT') || define('API_ROOT', dirname(__FILE__) . '/..');

require_once '../Library/ImagePath/Lib.php';

//可以配置多个访问的目录
 $spacePath = [
     'uploads'=>'uploads',//第一个为默认空间路径
     'images'=>'static/images',
 ];
 $f = new ImagePath_Lib($spacePath);
$imagePath->show();
```
//文件路径由 $spacePath + $_GET['f'] 组成
此时，完整的访问路径
```
xxx.com/file.php?f=test/1.jpg&sp=images&t=jpg
//对应的文件路径 xxx.com/static/images/test/1.jpg
```
 参数解析 
```
//sp参数是空间路径，上述的spacePath配置的key,不传默认$spacePath第一个
//f参数是在空间路径的基础上的文件路径
```
