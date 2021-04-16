<?php
/*
 * @Author: Brightness
 * @Date: 2020-10-23 17:58:15
 * @LastEditors: Brightness
 * @LastEditTime: 2021-04-12 11:05:47
 * @Description: 测试 PHPExcelHandle
 */

date_default_timezone_set('Asia/Shanghai'); #设置时区，防止报错

include_once '../PHPExcel/IOFactory.php';
include_once '../PHPExcel/Writer/Excel5.php';
include_once './handle.php';
$filePath = 'engine.xls';
//加载excel文件
$objPHPExcel = PHPExcel_IOFactory::load($filePath);

$handle_obj = new PHPExcelHandle($objPHPExcel);

//读取单元格值
$cellValue = $handle_obj->getCellValue('B1');

//设置单元格值
$handle_obj->setCellValue('B1', '新值');

//插入新行
$handle_obj->insertNewRowBefore(1, 1); #在1号行前插入1行

//删除行
$handle_obj->removeRow(6, 10); #从第6行往后删去10行

//插入列
$handle_obj->insertNewColumnBefore('E', 5);  #从第E列前添加5列

//删除列
$handle_obj->removeColumn('E', 5); #从E列开始往后删去5列

//用数组填充表  ,把数组的内容从A7开始填充,二维数组，一组数组一行,一个下标一个单元格
$dataArray = array(
    array("2010",    "Q1",  "United States",  790),
    array("2010",    "Q2",  "United States",  730),
);
$handle_obj->setCellValueFormArray('A7', $dataArray);

//添加图片
$objDrawing = new PHPExcel_Worksheet_Drawing();
$handle_obj->addImage($objDrawing, 'pic.png', 300, 300, 'F1', 'name', 'desc');
//合并单元格
// $handle_obj->mergeCells('A2','B2');
//获取第几列的字母
$rs = $handle_obj->get_zimu(2);
//复制单元格
$rs = $handle_obj->copyCell('B2', 'A10');
//剪切单元格
$handle_obj->cutCell('B2', 'B10');

//复制行
$rs = $handle_obj->copyRow(7, 11);
//剪切行
$handle_obj->cutRow(7, 13);
//复制列
$rs = $handle_obj->copyColumn('A', 'K', 10, 11);
//剪切列
$handle_obj->cutColumn('A', 'v');


//另存为文件
$handle_obj->save(PHPExcel_IOFactory, 'simple' . time() . '.xlsx');
