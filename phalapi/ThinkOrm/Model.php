<?php
/*
 * @Author: Brightness
 * @Date: 2022-04-04 23:20:42
 * @LastEditors: Brightness
 * @LastEditTime: 2022-04-04 23:20:43
 * @Description:  
*/

use think\Model;
use think\Exception;
class ThinkOrm_Model extends Model{
    
    /**
     * 模型名称,必须设置，值为省略表前缀的表名
     * @var string
     */
    protected $name;

    public function __construct($data = [])
    {
        if(empty($this->name)&&empty($this->table)){
            throw new Exception( __CLASS__."未设置name属性");
        }
        parent::__construct($data);
    }
}