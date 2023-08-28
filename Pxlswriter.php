<?php
/**
 * xlswriter简单封装
 */

namespace app\common\library;
set_time_limit(0);

use \Vtiful\Kernel\Format;
use \Vtiful\Kernel\Excel;

class Pxlswriter extends Excel
{
    /**********************************************样式常量*****************************************************/
    const FORMAT_ALIGN_LEFT = Format::FORMAT_ALIGN_LEFT;                                    // 水平左对齐
    const FORMAT_ALIGN_CENTER = Format::FORMAT_ALIGN_CENTER;                                // 水平剧中对齐
    const FORMAT_ALIGN_RIGHT = Format::FORMAT_ALIGN_RIGHT;                                  // 水平右对齐
    const FORMAT_ALIGN_FILL = Format::FORMAT_ALIGN_FILL;                                    // 水平填充对齐
    const FORMAT_ALIGN_JUSTIFY = Format::FORMAT_ALIGN_JUSTIFY;                              // 水平两端对齐
    const FORMAT_ALIGN_CENTER_ACROSS = Format::FORMAT_ALIGN_CENTER_ACROSS;                  // 横向中心对齐
    const FORMAT_ALIGN_DISTRIBUTED = Format::FORMAT_ALIGN_DISTRIBUTED;                      // 分散对齐
    const FORMAT_ALIGN_VERTICAL_TOP = Format::FORMAT_ALIGN_VERTICAL_TOP;                    // 顶部垂直对齐
    const FORMAT_ALIGN_VERTICAL_BOTTOM = Format::FORMAT_ALIGN_VERTICAL_BOTTOM;              // 底部垂直对齐
    const FORMAT_ALIGN_VERTICAL_CENTER = Format::FORMAT_ALIGN_VERTICAL_CENTER;              // 垂直剧中对齐
    const FORMAT_ALIGN_VERTICAL_JUSTIFY = Format::FORMAT_ALIGN_VERTICAL_JUSTIFY;            // 垂直两端对齐
    const FORMAT_ALIGN_VERTICAL_DISTRIBUTED = Format::FORMAT_ALIGN_VERTICAL_DISTRIBUTED;    // 垂直分散对齐

    const UNDERLINE_SINGLE = Format::UNDERLINE_SINGLE;                                      // 单下划线
//    const UNDERLINE_DOUBLE = Format::UNDERLINE_DOUBLE;                                      // 双下划线
    const UNDERLINE_SINGLE_ACCOUNTING = Format::UNDERLINE_SINGLE_ACCOUNTING;                // 会计用单下划线
    const UNDERLINE_DOUBLE_ACCOUNTING = Format::UNDERLINE_DOUBLE_ACCOUNTING;                // 会计用双下划线

    const BORDER_THIN = Format::BORDER_THIN;                                                // 薄边框风格
    const BORDER_MEDIUM = Format::BORDER_MEDIUM;                                            // 中等边框风格
    const BORDER_DASHED = Format::BORDER_DASHED;                                            // 虚线边框风格
    const BORDER_DOTTED = Format::BORDER_DOTTED;                                            // 虚线边框样式
    const BORDER_THICK = Format::BORDER_THICK;                                              // 厚边框风格
    const BORDER_DOUBLE = Format::BORDER_DOUBLE;                                            // 双边风格
    const BORDER_HAIR = Format::BORDER_HAIR;                                                // 头发边框样式
    const BORDER_MEDIUM_DASHED = Format::BORDER_MEDIUM_DASHED;                              // 中等虚线边框样式
    const BORDER_DASH_DOT = Format::BORDER_DASH_DOT;                                        // 短划线边框样式
    const BORDER_MEDIUM_DASH_DOT = Format::BORDER_MEDIUM_DASH_DOT;                          // 中等点划线边框样式
    const BORDER_DASH_DOT_DOT = Format::BORDER_DASH_DOT_DOT;                                // Dash-dot-dot边框样式
    const BORDER_MEDIUM_DASH_DOT_DOT = Format::BORDER_MEDIUM_DASH_DOT_DOT;                  // 中等点划线边框样式
    const BORDER_SLANT_DASH_DOT = Format::BORDER_SLANT_DASH_DOT;                            // 倾斜的点划线边框风格

    const COLOR_BLACK = Format::COLOR_BLACK;
    const COLOR_BLUE = Format::COLOR_BLUE;
    const COLOR_BROWN = Format::COLOR_BROWN;
    const COLOR_CYAN = Format::COLOR_CYAN;
    const COLOR_GRAY = Format::COLOR_GRAY;
    const COLOR_GREEN = Format::COLOR_GREEN;
    const COLOR_LIME = Format::COLOR_LIME;
    const COLOR_MAGENTA = Format::COLOR_MAGENTA;
    const COLOR_NAVY = Format::COLOR_NAVY;
    const COLOR_ORANGE = Format::COLOR_ORANGE;
    const COLOR_PINK = Format::COLOR_PINK;
    const COLOR_PURPLE = Format::COLOR_PURPLE;
    const COLOR_RED = Format::COLOR_RED;
    const COLOR_SILVER = Format::COLOR_SILVER;
    const COLOR_WHITE = Format::COLOR_WHITE;
    const COLOR_YELLOW = Format::COLOR_YELLOW;
    /**********************************************样式常量*****************************************************/
    protected $m_config = [
        'path' => __DIR__,
        'maxColumnWidth' => 50,
    ];
    /**
     * [$fieldsCallback 设置字段回调函数]
     * @var array
     */
    public $m_fieldsCallback = [];
    /**
     * 表格头
     * @var array
     */
    public $m_header = [];
    /**
     * @var array 默认样式
     */
    public $m_defaultStyle = [];
    /**
     * excel 行索引
     * @var int
     */
    public static $s_rowIndex = 1;
    /**
     * 自适应列宽（各字段最大长度）
     * @var array
     */
    public $m_autoSize = [];
    /**
     * excel 列索引
     * @var int
     */
    public static $s_colIndex = 0;
    /**
     * Pxlswrite constructor.
     * @param array $_config
     */
    public function __construct($_config = array())
    {
        foreach ($_config as $k => $v) {
            $this->m_config[$k] = $v;
        }
        parent::__construct($this->m_config);
    }

    /**
     *  创建工作表
     * @param string $_fileName
     * @param string $_tableName
     * @return $this
     */
    public function fileName($_fileName, $_tableName = 'sheet1')
    {
        parent::fileName($_fileName, $_tableName);
        return $this;
    }

    /**
     *  设置字段
     * @param array $_field 字段定义数组 数据格式如下
     * [
     *  'name' => ['name' => '姓名','callback'=>'functionName'],
     *  'age' => ['name' => '年龄'],
     * ]
     * @return $this
     * @throws DataFormatException
     */
    public function field($_field)
    {
        if (!empty($_field)) {
            $this->m_fieldsCallback = array_merge($this->m_fieldsCallback, $_field);
        }
        if (empty($this->m_header)) {
            $this->header(array_column($_field, 'name'));
        }
        return $this;
    }

    /**
     *  设置表格头
     * @param array $_header
     * @param null $_formatHandler
     * @return mixed
     * @throws DataFormatException
     */
    public function header($_header, $_formatHandler = NULL)
    {
        if (count($_header) !== count($_header, 1)) {
            throw new DataFormatException('header数据格式错误,必须是一位数索引数组');
        }
        foreach ($_header as $k=>$v){
            //初始化列宽
            $this->m_autoSize[$k] = strlen($v);
        }
        $this->m_header = $_header;
        if(!empty($_formatHandler)){
            if (!is_resource($_formatHandler)) {
                $_formatHandler = $this->styleFormat($_formatHandler);
            }
            parent::header($_header, $_formatHandler);
        }else{
            parent::header($_header);
        }
        return $this;
    }

    /**
     *  设置表格数据
     * @param array $_data 二维索引数组
     * @return
     */
    public function data($_data)
    {
        $this->calculateColumnWidth($_data);
        return parent::data($_data);
    }

    /**
     * 计算单元格字段宽度
     * @param $_data
     */
    public function calculateColumnWidth($_data)
    {
        foreach ($_data as $k=>$v){
            foreach ($v as $key=>$value){
                $length = strlen($value);
                $size = $this->m_autoSize[$key] >= $length ? $this->m_autoSize[$key] : $length;
                if($size > $this->m_config['maxColumnWidth']){
                    $size = $this->m_config['maxColumnWidth'];
                }
                $this->m_autoSize[$key] = $size;
            }
        }
    }

    /**
     * 设置单元格自适应列宽
     * @param array $_range 单元列范围  e.g. ['A:B','C'] 为空则默认所有单元列
     * @return $this
     * @throws DataFormatException
     */
    public function setAutoSize(array $_range = [])
    {
        if(!empty($_range)){
            //指定列自适应最大宽度
            foreach($_range as $columns){
                $columnArr = explode(':',$columns);
                $start = strtoupper($columnArr[0]);
                $end = strtoupper(end($columnArr));
                for($i = $start;$i <= $end;$i++){
                    $width = $this->getColumnMaxWidth($i);
                    $this->setColumn($i.':'.$i,$width * 1.05);
                }
            }
        }else{
            //所有列自适应最大宽度
            foreach ($this->m_autoSize as $key => $value){
                $column = self::stringFromColumnIndex($key);
                $this->setColumn($column.':'.$column,$value * 1.05);
            }
        }
        return $this;
    }

    /**
     * 获取单元列最大宽度
     * @param string $_column
     * @return mixed
     */
    public function getColumnMaxWidth(string $_column)
    {
        $columnIndex = self::columnIndexFromString($_column);
        return $this->m_autoSize[$columnIndex];
    }


    /**
     *  字段过滤&格式化
     * @param array $_value 一维数组
     * @return array 处理之后的结果数组
     */
    public function filter($_value)
    {
        $temp = [];
        foreach ($this->m_fieldsCallback as $k => $v) {
            $temp[$k] = isset($_value[$k]) ? $_value[$k] : '';
            //回调字段处理方法
            if (isset($v['callback'])) {
                $temp[$k] = call_user_func($v['callback'], $temp[$k], $_value);
            }
        }
        return $temp;
    }

    /**
     *  文件下载
     * @param string $_filePath 文件绝对路径
     * @param bool $_isDelete 下载后是否删除原文件
     * @throws PathException
     */
    public function download($_filePath, $_isDelete = true)
    {
        if (dirname($_filePath) != $this->m_config['path']) {
            throw new PathException('未知文件路径:' . dirname($_filePath));
        }
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . end(explode('/', $_filePath)) . '"');
        header('Content-Length: ' . filesize($_filePath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        ob_clean();
        flush();

        if (copy($_filePath, 'php://output') === false) {
            // Throw exception
        }
        if ($_isDelete) {
            @unlink($_filePath);
        }
    }

    /**
     *  打开文件
     * @param string $_fileName 文件名称
     * @return mixed
     */
    public function openFile($_fileName)
    {
        return parent::openFile($_fileName);
    }

    /**
     *  读取表格
     * @param string $_fileName
     * @return mixed
     */
    public function import($_fileName)
    {
        $data = $this
            ->openFile($_fileName)
            ->openSheet()
            ->getSheetData();
        return $data;
    }

    /**
     *  写日志
     * @param string $_message
     * @param array $_arr
     */
    public function writeLog($_message, array $_arr)
    {
        $dir = rtrim($this->m_config['path'], '/') . '/log/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $time = date('Y-m-d H:i:s');
        file_put_contents($dir . date("Y-m-d") . "_error.log", "[{$time}] " . $_message . PHP_EOL . serialize($_arr) . PHP_EOL, FILE_APPEND);
    }

    /**
     *  格式化样式
     * @param array $_style 样式列表数组
     * @return Format resource
     * @throws DataFormatException
     */
    public function styleFormat($_style)
    {
        $format = new Format($this->getHandle());
        $_style = empty($_style) ? [] : $_style;
        //合并全局样式
        $_style = array_merge($this->m_defaultStyle,$_style);
        foreach ($_style as $key => $value) {
            switch ($key) {
                case 'align':
                    if (!is_array($value) || count($value) != 2) {
                        throw new DataFormatException('align 数据格式错误');
                    }
                    $format->align($value[0], $value[1]);
                    break;
                default:
                    if (is_bool($value)) {
                        if ($value === true) {
                            $format->$key();
                        }
                    } else {
                        $format->$key($value);
                    }
            }
        }
        return $format->toResource();
    }

    /**
     *  行单元格样式
     * @param string $_range 单元格范围
     * @param int|double $_height 单元格高度  -1 默认行高13.5镑
     * @param resource|array $_formatHandler 单元格样式
     * @return $this
     * @throws DataFormatException
     */
    public function setRow($_range, $_height = -1, $_formatHandler = null)
    {
        if (!is_resource($_formatHandler)) {
            $_formatHandler = $this->styleFormat($_formatHandler);
        }
        if($_height == -1){
            parent::setRow($_range, 13.5, $_formatHandler);
        }else{
            parent::setRow($_range, $_height, $_formatHandler);
        }

        return $this;
    }

    /**
     * 列单元格样式
     * @param string $_range 单元格范围  e.g.  'A:C'
     * @param int|double $_width 单元格宽度  -1 自适列宽
     * @param resource|array $_formatHandler 单元格样式
     * @return $this
     * @throws DataFormatException
     */
    public function setColumn($_range, $_width = -1, $_formatHandler = null)
    {
        if (!is_resource($_formatHandler)) {
            $_formatHandler = $this->styleFormat($_formatHandler);
        }
        if($_width == -1){
            //自适应列宽
            $columnArr = explode(':',$_range);
            $start = strtoupper($columnArr[0]);
            $end = strtoupper(end($columnArr));
            for($i = $start;$i <= $end;$i++) {
                $_width = $this->getColumnMaxWidth($i) * 1.05;
                parent::setColumn($i.':'.$i,$_width,$_formatHandler);
            }
        }else{
            parent::setColumn($_range, $_width, $_formatHandler);
        }

        return $this;
    }

    /**
     *  合并单元格
     * @param string $_scope 单元格范围
     * @param string $_data data
     * @param resource|array $_formatHandler 合并单元格的样式
     * @return $this
     * @throws DataFormatException
     */
    public function mergeCells($_scope, $_data, $_formatHandler = null)
    {
        if(!empty($_formatHandler)){
            if (!is_resource($_formatHandler)) {
                $_formatHandler = $this->styleFormat($_formatHandler);
            }
            parent::mergeCells($_scope, $_data, $_formatHandler);
        }else{
            parent::mergeCells($_scope, $_data);
        }

        return $this;
    }

    /**
     *  全局默认样式 对setRow,setColumn,insertUrl,insertText方法有效
     * @param resource|array $_formatHandler style
     * @return $this
     * @throws DataFormatException
     */
    public function setDefaultStyle($_formatHandler)
    {
        if (!is_resource($_formatHandler)) {
            $this->m_defaultStyle = $_formatHandler;
            $_formatHandler = $this->styleFormat($_formatHandler);
        }

//        parent::defaultFormat($_formatHandler);
        return $this;
    }

    /**
     *	String from columnindex
     *
     *	@param	int $_columnIndex Column index (base 0 !!!)
     *	@return	string
     */
    public static function stringFromColumnIndex($_columnIndex = 0)
    {
        //	Using a lookup cache adds a slight memory overhead, but boosts speed
        //	caching using a static within the method is faster than a class static,
        //		though it's additional memory overhead
        static $s_indexCache = array();

        if (!isset($s_indexCache[$_columnIndex])) {
            // Determine column string
            if ($_columnIndex < 26) {
                $s_indexCache[$_columnIndex] = chr(65 + $_columnIndex);
            } elseif ($_columnIndex < 702) {
                $s_indexCache[$_columnIndex] = chr(64 + ($_columnIndex / 26)) .
                    chr(65 + $_columnIndex % 26);
            } else {
                $s_indexCache[$_columnIndex] = chr(64 + (($_columnIndex - 26) / 676)) .
                    chr(65 + ((($_columnIndex - 26) % 676) / 26)) .
                    chr(65 + $_columnIndex % 26);
            }
        }
        return $s_indexCache[$_columnIndex];
    }

    /**
     * @param int $_row 行 从0开始
     * @param int $_col 列 从0开始
     * @param string $_data 数据
     * @param string $_format 数据格式
     * @param array $_formatHandler 单元格样式
     * @return $this
     * @throws DataFormatException
     */
    public function insertText($_row, $_col, $_data, $_format = '', $_formatHandler=[])
    {
        if (!is_resource($_formatHandler)) {
            $_formatHandler = $this->styleFormat($_formatHandler);
        }

        parent::insertText($_row,$_col,$_data,$_format,$_formatHandler);
        
        return $this;
    }

    /**
     * 插入链接
     * @param int $_row 行 从0开始
     * @param int $_col 列 从0开始
     * @param string $_url  链接地址
     * @param string $text
     * @param string $tool_tip
     * @param array $_formatHandler 单元格样式
     * @return $this
     * @throws DataFormatException
     */
    public function insertUrl($_row,$_col,$_url,$text = NULL, $tool_tip = NULL, $_formatHandler = [])
    {
        if (!is_resource($_formatHandler)) {
            $_formatHandler = $this->styleFormat($_formatHandler);
        }
        parent::insertUrl($_row, $_col, $_url,$text = NULL, $tool_tip = NULL, $_formatHandler);
        return $this;
    }

    /**
     * 设置数据，逐行逐列插入数据，可以区分文本插入和超链接插入
     * @param $_data
     * @param int $_rowIndex 单元行索引(起始位置为0)
     * @param int $_coleIndex 单元列索引(起始位置为0)
     * @throws DataFormatException
     */
    public function setData($_data,$_rowIndex = 1,$_coleIndex = 0)
    {
        if($_rowIndex != 1){
            self::$s_rowIndex = $_rowIndex;
        }
        if($_coleIndex !== 0){
            self::$s_colIndex = $_coleIndex;
        }
        foreach($_data as $item){
            self::$s_colIndex = $_coleIndex;
            foreach ($item as $key=>$value){
                if($isMatched = preg_match('/http(?:s?):\/\//', $value)){
                    $this->insertUrl(self::$s_rowIndex,self::$s_colIndex,$value);
                }else{
                    $this->insertText(self::$s_rowIndex,self::$s_colIndex,$value);
                }
                self::$s_colIndex++;
            }
            self::$s_rowIndex++;
        }
    }

    public function output(){
        return parent::output();
    }
}