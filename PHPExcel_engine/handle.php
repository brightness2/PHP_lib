<?php
/*
 * @Author: Brightness
 * @Date: 2020-10-23 17:41:55
 * @LastEditors: Brightness
 * @LastEditTime: 2021-04-12 16:20:41
 * @Description: phpexcel 操作类，集合phpexcel的操作
 */
class PHPExcelHandle
{

    public $objPHPExcel = ''; #PHPExcel对象
    
    protected $activeSheet = ''; #sheet对象

    public function __construct($objPHPExcel)
    {
        $this->objPHPExcel = $objPHPExcel;
        $this->activeSheet = $this->objPHPExcel->getActiveSheet();
    }
    /**
     * 设置活动的sheet
     *
     * @param integer $index
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function setActiveSheet($index = 0)
    {
        $this->objPHPExcel->setActiveSheetIndex($index);
        $this->activeSheet = $this->objPHPExcel->getActiveSheet();
    }
    /**
     * 获取sheet个数
     *
     * @return integer
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getSheetCount()
    {
        return $this->objPHPExcel->getSheetCount();
    }
    /**
     * 获取最后一列 字母
     *
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getHighestColumn()
    {
        return $this->activeSheet->getHighestColumn();
    }
    /**
     * 获取后一行 数字
     *
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getHighestRow()
    {
        return $this->activeSheet->getHighestRow();
    }
    /**
     * 读取单元格值
     *
     * @param string $cell A1
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getCellValue($cell)
    {
        return $this->activeSheet->getCell($cell)->getValue();
    }
    /**
     * 设置单元格值
     *
     * @param string $cell A1
     * @param string $value
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function setCellValue($cell, $value)
    {
        $this->activeSheet->setCellValue($cell, $value);
    }
    /**
     * 插入空白行
     *
     * @param integer $start 1
     * @param integer $number
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function insertNewRowBefore($start, $number = 1)
    {
        $this->activeSheet->insertNewRowBefore($start, $number); #在$start号行前插入$number行
    }
    /**
     * 删除行
     *
     * @param integer $start 1
     * @param integer $number
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function removeRow($start, $number = 1)
    {
        $this->activeSheet->removeRow($start, $number); #从第$start行往后删去$number行
    }
    /**
     * 插入列
     *
     * @param string $start A
     * @param integer $number
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function insertNewColumnBefore($start, $number = 1)
    {
        $this->activeSheet->insertNewColumnBefore($start, $number);  #从第$start列前添加$number列
    }
    /**
     * 删除列
     *
     * @param string $start A
     * @param integer $number
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function removeColumn($start, $number = 1)
    {
        $this->activeSheet->removeColumn($start, $number);
    }
    /**
     * 用数组填充单元格
     *
     * @param string $beginCell
     * @param array $data
     * @return void
     * @desc //把数组的内容从 $beginCell 开始填充,二维数组，一组数组一行,一个下标一个单元格
     * @example
     * @author Brightness
     * @since
     */
    public function setCellValueFormArray($beginCell, $data)
    {
        $this->activeSheet->fromArray($data, NULL, $beginCell);
    }

    /**
     * 添加图片
     *
     * @param object $objDrawing  PHPExcel_Worksheet_Drawing对象
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
    public function addImage($objDrawing, $file, $height = 200, $width = 200, $cell, $name = '', $desc = '')
    {
        $objDrawing->setName($name);
        $objDrawing->setDescription($desc);
        $objDrawing->setPath($file);
        $objDrawing->setHeight($height);
        $objDrawing->setWidth($width);
        $objDrawing->setCoordinates($cell);
        $objDrawing->setWorksheet($this->activeSheet);
    }
    /**
     * 获取所有sheet
     *
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getWorksheetIterator()
    {
        return $this->objPHPExcel->getWorksheetIterator();
    }
    /**
     * 获取所有行
     *
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getRowIterator()
    {
        return  $this->activeSheet->getRowIterator();
    }
    /**
     * 获取某一行规格
     *
     * @param int $index 1
     * @return void
     * @desc 用于设置样式
     * @example
     * @author Brightness
     * @since
     */
    public function getRowDimension($index)
    {
        $row =  $this->activeSheet->getRowDimension($index);
        return $row;
    }
    /**
     * 获取某一列规格
     *
     * @param string $index A
     * @return void
     * @desc 用于设置样式
     * @example
     * @author Brightness
     * @since
     */
    public function getColumnDimension($index)
    {
        $col = $this->activeSheet->getColumnDimension($index);
        return $col;
    }
    /**
     * 获取单元格样式
     *
     * @param string $cell A1
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function getStyle($cell)
    {
        return $this->activeSheet->getStyle($cell);
    }
    /**
     * 合并单元格
     *
     * @param string $cellStr  A1:B1
     * @return void
     * @desc 
     * @example
     * @author Brightness
     * @since
     */
    public function mergeCells($cellStr)
    {
        $this->activeSheet->mergeCells($cellStr);
    }
    /**
     * 分离单元格
     *
     * @param string $cellStr A1:B1
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function unmergeCells($cellStr)
    {
        $this->activeSheet->unmergeCells($cellStr);
    }
    /**
     * 复制单元格
     *
     * @param string $cell1 要复制的单元格 A1:B1
     * @param string $cell2 新的单元格
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function copyCell($cell1, $cell2)
    {

        $cellValue = $this->getCellValue($cell1);
        $style = $this->getStyle($cell1);
        $this->setCellValue($cell2, $cellValue);
        $this->activeSheet->duplicateStyle($style, $cell2);
    }
    /**
     * 剪切单元格
     *
     * @param string $cell1
     * @param string $cell2
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function cutCell($cell1, $cell2)
    {
        $cellValue = $this->getCellValue($cell1);
        $style = $this->getStyle($cell1);

        $this->setCellValue($cell2, $cellValue);
        $this->setCellValue($cell1, '');
        $this->activeSheet->duplicateStyle($style, $cell2);
    }
    /**
     * 复制行
     *
     * @param int $index1
     * @param int $index2
     * @param string $startCol
     * @param string $endCol
     * @return void
     * @desc 不带样式
     * @example
     * @author Brightness
     * @since
     */
    // public function copyRow($index1,$index2,$startCol='A',$endCol="")
    // {
    //     $endCol = $endCol?$endCol:$this->getHighestColumn();

    //     $cellValues = $this->activeSheet->rangeToArray($startCol.$index1.':'.$endCol.$index1);
    //     $this->activeSheet->fromArray($cellValues, null, $startCol.$index2);
    // }

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
        if (!$startColIndex or !$endColIndex) return false;

        for ($i = $startColIndex; $i < $endColIndex; $i++) {
            $currCol = $this->get_zimu($i);
            $this->copyCell($currCol . $index1, $currCol . $index2);
        }
    }
    /**
     * 剪切行
     *
     * @param int $index1
     * @param int $index2
     * @param string $startCol
     * @param string $endCol
     * @return void
     * @desc 不带样式
     * @example
     * @author Brightness
     * @since
     */
    // public function cutRow($index1,$index2,$startCol='A',$endCol="")
    // {
    //     $endCol = $endCol?$endCol:$this->getHighestColumn();

    //     $cellValues = $this->activeSheet->rangeToArray($startCol.$index1.':'.$endCol.$index1);
    //     $this->activeSheet->fromArray($cellValues, null, $startCol.$index2);
    //     $this->removeRow($index1,1);
    // }
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
        if (!$startColIndex or !$endColIndex) return false;

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
        if (!is_int($startRow) or !is_int($endRow)) return false;
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
        if (!is_int($startRow) or !is_int($endRow)) return false;
        $endRow = $endRow ? $endRow : $this->getHighestRow();
        for ($i = $startRow; $i < $endRow; $i++) {
            $this->cutCell($col1 . $i, $col2 . $i);
        }
        if (true == $delCol) $this->removeColumn($col1);
    }
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
    /**
     * 另存为
     *
     * @param object $objIoFactory    PHPExcel_IOFactory对象
     * @param string $filename  文件名 含路径
     * @param string $versions
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function save($objIoFactory, $filename, $versions = 'Excel2007')
    {
        $objWriter = $objIoFactory::createWriter($this->objPHPExcel, $versions);
        $objWriter->save($filename);
    }
    /**
     * 直接下载
     *
     * @param object $objIoFactory    PHPExcel_IOFactory对象
     * @param string $filename
     * @param string $versions
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function download($objIoFactory, $filename, $versions = 'Excel2007')
    {
        header("Content-type:application/vnd.ms-excel");  //设置内容类型
        header("Content-Disposition:attachment;filename=" . $filename);  //文件下载
        $objWriter = $objIoFactory::createWriter($this->objPHPExcel, $versions);
        $objWriter->save('php://output'); //文件保存路径
    }

    /*
    //获取所有行
    $rowList = $activeSheet->getRowIterator();


    //遍历行
    foreach ($rowList as $key => $row) {       //遍历行
        
        echo '    Row number - ' , $row->getRowIndex() , PHP_EOL;
    $cellIterator = $row->getCellIterator();   //得到所有列
    $cellIterator->setIterateOnlyExistingCells( false); // Loop all cells, even if it is not set
    }
    echo '<br/>';

    //遍历列
    foreach ($cellIterator as $cell) {  //遍历列
        if (!is_null($cell)) {  //如果列不给空就得到它的坐标和计算的值
            echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , PHP_EOL;
    }
    }
    */
}
