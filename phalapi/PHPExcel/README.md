# EXCEL 文件操作扩展
基于 PHPExcel 的excel操作扩展

## 使用
1.基本使用
```php
$file = API_ROOT.'/Public/test.xls';
//实例化Lib
$obj = new PHPExcel_Lib();
//导入excel文件
$obj->importExcel($file);
//获取当前excel文件操作句柄，具体操作方法 查看 PHPExcel_Handle 类
$handle = $obj->getHandle();
//愉快地使用
return $handle->getCellValue('A3');
//...
```
2.使用PHPExcel原操作方法
```php
$file = API_ROOT.'/Public/test.xls';
//实例化Lib
$obj = new PHPExcel_Lib();
//导入excel文件
$obj->importExcel($file);
//获取excel对象
$excelObj = $obj->getExcelObj();
return $excelObj->getActiveSheet()->getCell('A3')->getValue();
```