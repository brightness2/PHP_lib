<?php
namespace app\common\library;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use think\exception\ValidateException;

/*
 * @Author: Brightness
 * @Date: 2023-06-12 17:41:55
 * @LastEditors: Brightness
 * @LastEditTime: 2023-06-13 16:20:41
 * @Description: PhpSpreadsheet 操作类，集合PhpSpreadsheet的操作
 */

 class PhpSpreadsheetExcelHandle
 {
    public $spreadsheet = ''; #PhpSpreadsheetExcel对象

    protected $activeSheet = ''; #sheet对象

    protected $drawing = null;

    public function __construct(Spreadsheet $spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
    }
    /*****************worksheet 操作*************************** */

    /**
     * 获取worksheet个数
     *
     * @return int
     */
    public function getSheetCount() {
      return $this->spreadsheet->getSheetCount();
    }

    /**
     * 获取worksheet 名称 集合
     *
     * @return array
     */
    public function getSheetNames(){
        return $this->spreadsheet->getSheetNames();
    }

    /**
     * 获取worksheet 对象
     *
     * @param integer/string $index
     * @return Worksheet
     */
    public function getSheet($index){
        if(is_numeric($index)){
            $worksheet =  $this->spreadsheet->getSheet($index);
        }else{
            $worksheet =  $this->spreadsheet->getSheetByName($index);
        }
        if($worksheet){
            return clone $worksheet;
        }else{
            return false;
        }
    }

    /**
     * 获取某个sheet的index
     * @desc 如果是数字就直接返回整数类型
     * @param string $title
     * @return integer/false
     */
    public function getSheetIndex($title){
        if(is_numeric($title)){
            return intval($title);
        }
        $workSheet = $this->spreadsheet->getSheetByName($title);
        if(!$workSheet){
            return false;
        }
        return $this->spreadsheet->getIndex($workSheet);
    }

    /**
     * 设置活动的sheet
     *
     * @param integer/string $index
     * @return PhpSpreadsheetExcelHandle
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function setActiveSheet($index){
        if(is_numeric($index)){
            $this->spreadsheet->setActiveSheetIndex($index);
        }else{
            $this->spreadsheet->setActiveSheetIndexByName($index);
        }
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }

    /**
     * 创建sheet
     *
     * @param string $title sheet名称
     * @param integer/string $afterIndex 在第几个sheet后面添加
     * @return PhpSpreadsheetExcelHandle
     */
    public function createSheet($title,$afterIndex = 0) {
        $worksheet_exist =  $this->getSheetIndex($title);
        if(false !== $worksheet_exist){
            throw new ValidateException('"'.$title.'" worksheet 已存在');
            
        }
        $workSheet = new Worksheet($this->spreadsheet,$title);
        $this->spreadsheet->addSheet($workSheet,$this->getSheetIndex($afterIndex)??0);
        
        return $this;
    }

    /**
     * 复制sheet
     *
     * @param integer/string $copyIndex
     * @param string $newSheetTitle
     * @param int $newSheetIndex 如果为null则后加到最后，不能setActiveSheet
     * @return PhpSpreadsheetExcelHandle
     */
    public function copySheet($copyIndex,$newSheetTitle,$newSheetIndex=0){
        if(is_numeric($copyIndex)){
            $clonedWorksheet = clone $this->spreadsheet->getSheet($copyIndex);
        }else{
            $clonedWorksheet = clone $this->spreadsheet->getSheetByName($copyIndex);
        }
        // $clonedWorksheet = $this->getSheet($copyIndex);
        // if(!$clonedWorksheet){
        //     throw new ValidateException('"'.$copyIndex.'" worksheet 不存在');
        // }
        $clonedWorksheet->setTitle($newSheetTitle);
        $this->spreadsheet->addSheet($clonedWorksheet,$newSheetIndex);
        return $this;
    }

    /**
     * 移除sheet
     *
     * @param integer/string $index
     * @return PhpSpreadsheetExcelHandle
     */
    public function removeSheet($index) {
        $_index = $this->getSheetIndex($index);
        if(false !== $_index){
            $this->spreadsheet->removeSheetByIndex($_index);
        }
        return $this;
    }

    /***********************Cell单元格操作******************************* */
    /**
     * 获取单元格内容
     *
     * @param string $cell 'A1'
     * @return mixed
     */
    public function getCellValue($cell) {
        return $this->activeSheet->getCell($cell)->getValue();
    }

    /**
     * 通过单元格坐标获取值
     *
     * @param int $col
     * @param int $row
     * @return mixed
     */
    public function getCellValueByColumnAndRow($col, $row) {
      return  $this->activeSheet->getCellByColumnAndRow($col, $row)->getValue();
    }

    /**
     * 读取区域数据为数组
     * @param string $pRang 'A1:E5'
     * @return void
     * @example
     * 结果例子
        {
            "1": {
                "A": "Hello World !",
                "B": "a",
                "C": "b"
            },
            "2": {
                "A": null,
                "B": "c",
                "C": null
            },
        }
     */
    public function getValueToArray($pRang) {
      return  $this->activeSheet->rangeToArray($pRang,NULL,TRUE,TRUE,TRUE);
    }

    /**
     * 设置单元格内容
     *
     * @param string $cell 'A1'
     * @param mixed $value
     * @return PhpSpreadsheetExcelHandle
     */
    public function setCellValue($cell,$value){
        $this->activeSheet->setCellValue($cell,$value);
        return $this;
    }

    /**
     * 通过单元格坐标设置值
     *
     * @param int $col
     * @param int $row
     * @param mixed $value
     * @return PhpSpreadsheetExcelHandle
     */
    public function setCellValueByColumnAndRow($col,$row,$value){
        $this->activeSheet->setCellValueByColumnAndRow($col,$row,$value);
        return $this;
    }

    

    /**
     * 用数组填充单元格
     *
     * @param string $beginCell
     * @param array $data
     * @return PhpSpreadsheetExcelHandle
     * @desc //把数组的内容从 $beginCell 开始填充,二维数组，一组数组一行,一个下标一个单元格
     * @example
     * @author Brightness
     * @since
     */
    public function setCellValueFormArray($beginCell,$data){
        $this->activeSheet->fromArray($data,null,$beginCell);
        return $this;
    }

    public function getHighestColumn(){
       return $this->activeSheet->getHighestColumn();
    }

    public function getHighestRow(){
        return $this->activeSheet->getHighestRow();
    }

    /**
     * 插入空白行
     *
     * @param int $start
     * @param integer $number
     * @return PhpSpreadsheetExcelHandle
     */
    public function insertNewRowBefore($start, $number = 1){
        $this->activeSheet->insertNewRowBefore($start,$number);#在$start号行前插入$number行
        return $this;
    }
    /**
     * 删除行
     *
     * @param int $start
     * @param integer $number
     * @return PhpSpreadsheetExcelHandle
     */
    public function removeRow($start,$number = 1) {
        $this->activeSheet->removeRow($start,$number);
        return $this;
    }

    /**
     * 插入空白列
     *
     * @param string $start 'A‘
     * @param integer $number
     * @return PhpSpreadsheetExcelHandle
     */
    public function insertNewColumnBefore($start, $number = 1) {
        $this->activeSheet->insertNewColumnBefore($start, $number); #从第$start列前添加$number列
        return $this;      
    }

    /**
     * 删除列
     *
     * @param string $start 'A'
     * @param integer $number
     * @return PhpSpreadsheetExcelHandle
     */
    public function removeColumn($start,$number = 1){
        $this->activeSheet->removeColumn($start,$number);
        return $this;
    }
    /**
     * 获取单元格样式
     *
     * @param string $cell A1
     */
    public function getStyle($cell)
    {
        return $this->activeSheet->getStyle($cell);
    }

    /**
     * 复制单元格
     *
     * @param string $copyCell 'A1'
     * @param string $newCell  'B1'
     * @return PhpSpreadsheetExcelHandle
     */
    public function copyCell($copyCell,$newCell){
        $cellValue = $this->getCellValue($copyCell);
        $style = $this->getStyle($copyCell);
        $this->setCellValue($newCell,$cellValue);
        $this->activeSheet->duplicateStyle($style,$newCell);
        return $this;
    }
    
    /**
     * 剪切单元格
     *
     * @param string $copyCell
     * @param string $newCell
     * @return PhpSpreadsheetExcelHandle
     */
    public function cutCell($copyCell, $newCell)
    {
        $cellValue = $this->getCellValue($copyCell);
        $copyStyle = $this->getStyle($copyCell);
        $this->setCellValue($newCell, $cellValue);
        $this->setCellValue($copyCell, '');
        $this->activeSheet->duplicateStyle($copyStyle, $newCell);
        return $this;
    }

    /**
     * 复制行
     *
     * @param int $index1
     * @param int $index2
     * @param string $startCol
     * @param string $endCol
     * @return void
     * @desc 带样式
     * @example
     * @author Brightness
     * @since
     */
    public function copyRow($index1, $index2, $startCol = 'A', $endCol = "")
    {
        $endCol = $endCol ? $endCol : $this->getHighestColumn();
        $startColIndex = $this->get_index($startCol);
        $endColIndex = $this->get_index($endCol);
        if (!$startColIndex or !$endColIndex){
            throw new ValidateException('列坐标参数错误');
        }
        for ($i = $startColIndex; $i < $endColIndex; $i++) {
            $currCol = $this->get_zimu($i);
            $this->copyCell($currCol . $index1, $currCol . $index2);
        }
        return $this;
    }

     /**
     * 剪切行
     *
     * @param int $index1
     * @param int $index2
     * @param string $startCol
     * @param string $endCol
     * @return void
     * @desc 带样式
     * @example
     * @author Brightness
     * @since
     */
    public function cutRow($index1, $index2, $startCol = 'A', $endCol = "", $delRow = false)
    {
        $endCol = $endCol ? $endCol : $this->getHighestColumn();
        $startColIndex = $this->get_index($startCol);
        $endColIndex = $this->get_index($endCol);
        if (!$startColIndex or !$endColIndex){
            throw new ValidateException('列坐标参数错误');
        }
        for ($i = $startColIndex; $i < $endColIndex; $i++) {
            $currCol = $this->get_zimu($i);
            $this->cutCell($currCol . $index1, $currCol . $index2);
        }
        if (true == $delRow) $this->removeRow($index1);
    }

    /**
     * 复制列
     *
     * @param string $col1 A
     * @param string $col2 B
     * @param integer $startRow 开始行
     * @param integer $endRow   结束行
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function copyColumn($col1, $col2, $startRow = 1, $endRow = 0)
    {
        if (!is_int($startRow) or !is_int($endRow)){
            throw new ValidateException('横坐标参数错误');
        }
        $endRow = $endRow ? $endRow : $this->getHighestRow();
        for ($i = $startRow; $i < $endRow; $i++) {
            $this->copyCell($col1 . $i, $col2 . $i);
        }
    }
     /**
     * 剪切列
     *
     * @param string $col1
     * @param string $col2
     * @param integer $startRow
     * @param integer $endRow
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function cutColumn($col1, $col2, $startRow = 1, $endRow = 0, $delCol = false)
    {
        if (!is_int($startRow) or !is_int($endRow)){
            throw new ValidateException('横坐标参数错误');
        }
        $endRow = $endRow ? $endRow : $this->getHighestRow();
        for ($i = $startRow; $i < $endRow; $i++) {
            $this->cutCell($col1 . $i, $col2 . $i);
        }
        if (true == $delCol) $this->removeColumn($col1);
    }

    /**
     * 添加图片
     *
     * @param string $file  图片绝对路径
     * @param integer $height   图片高度
     * @param integer $width    图片宽度
     * @param string $cell  插入的单元格
     * @param string $name  图片名称
     * @param string $desc  图片描述
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function addImage($file, $height = 200, $width = 200, $cell, $name = '', $desc = '',$offsetX=0,$offsetY=0,$rotation=0)
    {
        
        $drawing = new Drawing();
        
        $drawing->setName($name);
        $drawing->setDescription($desc);
        $drawing->setPath($file);
        $drawing->setHeight($height);
        $drawing->setWidth($width);
        $drawing->setCoordinates($cell);
        $drawing->setOffsetX($offsetX);
        $drawing->setOffsetY($offsetY);
        $drawing->setRotation($rotation);
        $drawing->setWorksheet($this->activeSheet);
        return $this;
    }

    /*************************保存文件************************************* */
    public function save($fileName,$dir='',$ext='xlsx'){
        $ext = strtolower($ext);
        if($ext == 'xls'){
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($this->spreadsheet);

        }else{
            $ext = 'xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);

        }
        // $writer->setOffice2003Compatibility(true);# 2003
        // $writer->setPreCalculateFormulas(false); # 2007
        $file_path = rtrim($dir,"\/\\").'/'.$fileName.'.'.$ext;
        $writer->save($file_path);
        return str_replace(ROOT_PATH.'public','',$file_path);
    }

    /********************其它******************************************** */
     /**
     * 获取某一列的字母
     *
     * @param int $index
     * @param integer $start
     * @return string    例子: A AB
     * @desc 
     * @example
     * @author Brightness
     * @since
     */
    public function get_zimu($index, $start = 65)
    {
        $index = $index - 1;
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= $this->get_zimu(floor($index / 26) - 1);
        }

        return $str . chr($index % 26 + $start);
    }

    /**
     * 获取某一列数值
     *
     * @param string $col
     * @return int
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function get_index($col)
    {
        $col = strtoupper($col);
        $len =  strlen($col);
        if ($len > 2 or $len <= 0) return false;
        $base = 64;
        if ($len == 1) return ord($col) - $base;

        $firstChar = substr($col, 0, 1);
        $secondChar = substr($col, 1, 1);
        $count =  (ord($firstChar) - $base) % 26;
        return $count * 26 + ord($secondChar) - $base;
    }
 }