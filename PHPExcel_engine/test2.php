<?php
/*
 * @Author: Brightness
 * @Date: 2021-04-12 10:58:21
 * @LastEditors: Brightness
 * @LastEditTime: 2021-04-12 16:04:51
 * @Description:  材料清单模板导出
 */
date_default_timezone_set('Asia/Shanghai'); #设置时区，防止报错
include '../PHPExcel/IOFactory.php';
// include '../PHPExcel/Writer/Excel5.php';
include_once './handle.php';

$filePath = 'wu.xls';
//加载excel文件
$objPHPExcel = PHPExcel_IOFactory::load($filePath);
//实例化phpexcel 操作类
$handle_obj = new PHPExcelHandle($objPHPExcel);


/***********材料清单书封面********* */
//激活第一个sheet
$handle_obj->setActiveSheet(0);

//项目名称
$projectName = '昊朗项目';
$handle_obj->setCellValue('A17', $projectName);

//日期
$nowDate = date('Y-m-d');
$handle_obj->setCellValue('E19', $nowDate);

//材料类别
$category = '石材类别';
$handle_obj->setCellValue('A20', $category);

/**************目录************** */
//激活第二个sheet
$handle_obj->setActiveSheet(1);

//日期
$nowDate = date('Y-m-d');
$handle_obj->setCellValue('C2', $nowDate);

//材料类别 编号
$catNum = '类别：' . '石材类别' . '    编号：' . '123';
$handle_obj->setCellValue('B3', $catNum);

//项目 区域
$proArea = '项目：' . '昊朗项目' . '    区域：' . '广州';
$handle_obj->setCellValue('A4', $proArea);

//
$data = array(
    // 项目信息
    'project' => [
        'prj_name' => '昊朗项目',
        'date' => date('y-m-d'),
        'mtsCat' => '石材',
        'ProjectType' => '办公',
        'prj_id' => 1,
        'prj_area' => '广州',
    ],
    //目录信息,select_mts
    'catalog' => [
        [
            'mts_id' => 1,
            'CustName' => '商户1',
            'mts_name' => '花岗岩',
            'mts_mode' => '型号1',
            'mts_brand' => '规格1',
            'sel_area' => '厨房区域',
            'sel_memo' => '备注信息',
        ],
        [
            'mts_id' => 2,
            'CustName' => '商户2',
            'mts_name' => '花岗岩',
            'mts_mode' => '型号2',
            'mts_brand' => '规格2',
            'sel_area' => '客厅区域',
            'sel_memo' => '备注信息',
        ],
    ],
);
$handle_obj->copyRow(7, 6 + count($data['catalog']));
$handle_obj->setCellValueFormArray('A7', $data['catalog']);
//另存为文件
$handle_obj->save(PHPExcel_IOFactory, 'tmp/mySimple' . time() . '.xlsx');
