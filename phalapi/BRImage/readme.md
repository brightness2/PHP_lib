# 图像处理扩展
参考于thinkphp

## 使用
1.打开图片
```php
 $image = new BRImage_Lib($type=1);//$type 要使用的驱动，1 =》 GD库，2 =》imagick库
 $image->open($file);
```
2.基础用法
```php
$image = new BRImage_Lib();
$image->open($file);
$width = $image->width(); // 返回图片的宽度
$height = $image->height(); // 返回图片的高度
$type = $image->type(); // 返回图片的类型
$mime = $image->mime(); // 返回图片的mime类型
$size = $image->size(); // 返回图片的尺寸数组 0 图片宽度 1 图片高度
```

3.压缩裁剪
```php
/**
 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
 * BRImage_Lib::IMAGE_THUMB_SCALING = 1      //常量，标识缩略图等比例缩放类型
 * BRImage_Lib::IMAGE_THUMB_FILLED = 2       //常量，标识缩略图缩放后填充类型
 * BRImage_Lib::IMAGE_THUMB_CENTER = 3       //常量，标识缩略图居中裁剪类型
 * BRImage_Lib::IMAGE_THUMB_NORTHWEST = 4    //常量，标识缩略图左上角裁剪类型
 * BRImage_Lib::IMAGE_THUMB_SOUTHEAST = 5    //常量，标识缩略图右下角裁剪类型
 * BRImage_Lib::IMAGE_THUMB_FIXED = 6        //常量，标识缩略图固定尺寸缩放类型
 */
$image = new BRImage_Lib();
$image->open($file);
// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
$image->thumb(150, 150, IMAGE_THUMB_SCALING);
$image->save("thumb.jpg");
//将图片裁剪为400x400并保存为corp.jpg
$image->crop(400, 400)->save('./crop.jpg');
//将图片裁剪为400x400并保存为corp.jpg  从（100，30）开始裁剪
$image->crop(400, 400, 100, 30)->save('./crop.jpg');
```
4.图片水印
```php
/**
 * water方法的第二个参数表示水印的位置，可以传入下列常量或者对应的数字：
 * BRImage_Lib::IMAGE_WATER_NORTHWEST =   1 ; //左上角水印
 * BRImage_Lib::IMAGE_WATER_NORTH     =   2 ; //上居中水印
 * BRImage_Lib::IMAGE_WATER_NORTHEAST =   3 ; //右上角水印
 * BRImage_Lib::IMAGE_WATER_WEST      =   4 ; //左居中水印
 * BRImage_Lib::IMAGE_WATER_CENTER    =   5 ; //居中水印
 * BRImage_Lib::IMAGE_WATER_EAST      =   6 ; //右居中水印
 * BRImage_Lib::IMAGE_WATER_SOUTHWEST =   7 ; //左下角水印
 * BRImage_Lib::IMAGE_WATER_SOUTH     =   8 ; //下居中水印
 * BRImage_Lib::IMAGE_WATER_SOUTHEAST =   9 ; //右下角水印
 */


$image = new BRImage_Lib();
// 自定义水印坐标位置，传入数组array(x,y) 例如：
$image->open($file)->water(API_ROOT."/Public/your_file.png",BRImage_Lib::IMAGE_WATER_NORTHWEST,20)->save('./water.png');

```