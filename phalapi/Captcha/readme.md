# captcha 验证码
传统的图片验证码,注意：需要开启session

## 使用
ini.php 文件中注册captcha
```
//图片验证码
$di->captcha = new Captcha_Lib();
```
输出验证码图片
```
return DI()->captcha->create();
```
校验验证码
```
 return DI()->captcha->check('wyjk');
```
## 配置
config/app.php 文件配置
```
//captcha 验证码配置
'captcha'=> array(
    //验证码位数
    'length'   => 4,
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码过期时间
    'expire'   => 1800,
    // 是否使用中文验证码
    'useZh'    => false,
    // 是否使用算术验证码
    'math'     => false,
    // 是否使用背景图
    'useImgBg' => false,
    //验证码字符大小
    'fontSize' => 25,
    // 是否使用混淆曲线
    'useCurve' => true,
    //是否添加杂点
    'useNoise' => true,
    // 验证码字体 不设置则随机
    'fontttf'  => '',
    //背景颜色
    'bg'       => [243, 251, 254],
    // 验证码图片高度
    'imageH'   => 0,
    // 验证码图片宽度
    'imageW'   => 0,
),
```