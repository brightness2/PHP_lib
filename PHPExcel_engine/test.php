<?php
/*
 * @Author: Brightness
 * @Date: 2020-07-29 15:44:46
 * @LastEditors: Brightness
 * @LastEditTime: 2021-04-12 11:04:37
 * @Description: 测试 PHPExcel
 */
date_default_timezone_set('Asia/Shanghai'); #设置时区，防止报错

include '../PHPExcel/IOFactory.php';
include '../PHPExcel/Writer/Excel5.php';

$filePath = 'engine.xls';
//加载excel文件
$objPHPExcel = PHPExcel_IOFactory::load($filePath);
//获取excel sheet个数
$sheetCount = $objPHPExcel->getSheetCount();

echo 'sheet页数 ' . $sheetCount . ',下标从0开始<br/>';

//激活 某个sheet页面
$sheetSelected = 0;
$objPHPExcel->setActiveSheetIndex($sheetSelected);

//获取最大行数
$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();
echo '获取最大行数' . $rowCount . '<br/>';
//获取最大列数
$columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
echo '获取最大列数' . $columnCount . '<br/>';

//读取单元格值
$col = 'B';
$row = 1;
$val = $objPHPExcel->getActiveSheet()->getCell($col . $row)->getValue();
echo '单元B1值: ' . $val . '<br/>';

//设置单元格值
$newVal = '新值';
$objPHPExcel->getActiveSheet()->setCellValue($col . $row, $newVal);

//插入新行
$objPHPExcel->getActiveSheet()->insertNewRowBefore(1, 1); #在1号行前插入1行
//删除行
$objPHPExcel->getActiveSheet()->removeRow(6, 10); #从第6行往后删去10行
//插入列
$objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', 5);  #从第E列前添加5列
//删除列
$objPHPExcel->getActiveSheet()->removeColumn('E', 5); #从E列开始往后删去5列

//把数组的内容从A7开始填充,二维数组，一组数组一行,一个下标一个单元格
$dataArray = array(
    array("2010",    "Q1",  "United States",  790),
    array("2010",    "Q2",  "United States",  730),
);
$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A7');

//添加图片
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName("name");
$objDrawing->setDescription("desc");
$objDrawing->setPath('pic.png');
$objDrawing->setHeight(300);
$objDrawing->setWidth(300);
$objDrawing->setCoordinates("F1");
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//获取所有sheet
$sheetList = $objPHPExcel->getWorksheetIterator();
//获取激活的sheet
$activeSheet = $objPHPExcel->getActiveSheet();
//遍历工作表
foreach ($sheetList as $worksheet) {     //遍历工作表
    echo 'Worksheet - ', $worksheet->getTitle(), PHP_EOL;
}
echo '<br/>';
//获取所有行
$rowList = $activeSheet->getRowIterator();


//遍历行
foreach ($rowList as $key => $row) {       //遍历行

    echo '    Row number - ', $row->getRowIndex(), PHP_EOL;
    $cellIterator = $row->getCellIterator();   //得到所有列
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
}
echo '<br/>';

//遍历列
foreach ($cellIterator as $cell) {  //遍历列
    if (!is_null($cell)) {  //如果列不给空就得到它的坐标和计算的值
        echo '        Cell - ', $cell->getCoordinate(), ' - ', $cell->getCalculatedValue(), PHP_EOL;
    }
}
//复制单元格
$cellValues = $objPHPExcel->getActiveSheet()->rangeToArray('B2');
$objPHPExcel->getActiveSheet()->fromArray($cellValues, null, 'A9');
echo '<br/>';
var_dump($res);
echo "<br/>";
//复制样式
$style = $objPHPExcel->getActiveSheet()->getStyle('B2');
$objPHPExcel->getActiveSheet()->duplicateStyle($style, 'A7');
//另存为新的excel

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $objWriter->save('simple'.time().'.xlsx');

//所有函数
// $objCalc = PHPExcel_Calculation::getInstance();
// echo json_encode($objCalc->listFunctionNames());
