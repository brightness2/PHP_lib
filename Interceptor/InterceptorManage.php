<?php
/*
 * @Author: Brightness
 * @Date: 2022-03-31 11:28:51
 * @LastEditors: Brightness
 * @LastEditTime: 2022-03-31 11:28:51
 * @Description:  拦截器管理类
*/
namespace interceptor;
use interceptor\Interceptor;
class InterceptorManage{

    private $event = [];

    /**
     * 注册拦截器
     *
     * @param string $type
     * @param string $name
     * @return void
     */
    public function attachEvent($type,$name)
    {
        $obj = $this->createInterceptor($name);
        $this->event[$type][] = $obj;
    }

    public function action()
    {
        $this->raiseEven('before');

        echo 'center<br/>';

        $this->raiseEven('after');
    }

    private function raiseEven($type)
    {
        if(empty($this->event[$type])){
            return;
        }
        foreach($this->event[$type] as $item){
            $item->handle();
        }
    }

    /**
     * 实例化拦截器
     *
     * @param string $name
     * @return object
     */
    private function createInterceptor($name){
        if(!is_string($name)){
            throw new \Exception('name param must be a string');
        }
        if(!class_exists($name)){
            throw new \Exception("no such interceptor $name ");
        }
        $obj = new $name;
        if(!($obj instanceof Interceptor)){
            throw new \Exception("$name should be instanceof Interceptor");
        }

        return $obj;
    }


}
