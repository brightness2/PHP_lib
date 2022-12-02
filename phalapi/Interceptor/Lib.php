<?php
/*
 * @Author: Brightness
 * @Date: 2022-03-31 14:14:32
 * @LastEditors: Brightness
 * @LastEditTime: 2022-03-31 14:14:33
 * @Description:  拦截器 管理类
*/

class Interceptor_Lib{

    private $event = [];//存储拦截器实例

    const BEFORE = 'before';
    const AFTER = 'after';
    const InterceptorFun = 'handle';

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * 批量注册拦截器
     *
     * @param string $type 
     * @param array $classNameArr
     * @return void
     */
    public function attachByArr($type, $classNameArr)
    {
        if(!is_array($classNameArr)||empty($classNameArr)){
            return ;
        }
        foreach($classNameArr as $className){
            $this->attachEvent($type,$className);
        }
    }

    /**
     * 注册拦截器
     *
     * @param string $type
     * @param string $className
     * @return void
     */
    public function attachEvent($type,$className)
    {
        $obj = $this->createInterceptor($className);
        $this->event[$type][] = $obj;
    }

    /**
     * 执行api应用
     *
     * @param PhalApi_Api $api
     * @return void
     */
    public function action(PhalApi_Api $api)
    {
        $this->attachFromApi($api);
        $this->raiseEvent(self::BEFORE,$api);
        $action = DI()->request->getServiceAction();
        $data  = call_user_func(array($api, $action));//执行api中方法
        $this->raiseEvent(self::AFTER,$api,$data);
        return $data;
    }

    /**
     * 初始化
     *
     * @return void
     */
    private function init()
    {
        $beforeInterceptors = DI()->config->get('interceptor.beforeInterceptors');
        $afterInterceptors = DI()->config->get('interceptor.afterInterceptors');
        $this->attachByArr(self::BEFORE,$beforeInterceptors);
        $this->attachByArr(self::AFTER,$afterInterceptors);
         
    }

    /**
     * 注册api级别的拦截器
     * 执行顺序低于全局拦截器
     * @param Phalapi_Api $api
     * @return void
     */
    private function attachFromApi(PhalApi_Api $api)
    {
       $beforeInterceptors = property_exists($api,'beforeInterceptors') ? $api->beforeInterceptors : [];
       $afterInterceptors = property_exists($api,'afterInterceptors') ? $api->afterInterceptors : [];
       $this->attachByArr(self::BEFORE,$beforeInterceptors);
       $this->attachByArr(self::AFTER,$afterInterceptors);
    }

    /**
     * 批量触发拦截器
     *
     * @param string $type 'before' or 'after'
     * @param PhalApi_Api $api
     * @param mixed $data 
     * @return void
     */
    private function raiseEvent($type,PhalApi_Api $api,$data=null)
    {
        if(empty($this->event[$type])){
            return;
        }
        foreach($this->event[$type] as $item){
            call_user_func([$item,self::InterceptorFun], $api,$data);
        }
    }

    /**
     * 实例化拦截器
     *
     * @param string $className
     * @return object
     */
    private function createInterceptor($className){
        if(!is_string($className)){
            throw new \Exception('name param must be a string');
        }
        if(!class_exists($className)){
            throw new \Exception("no such interceptor $className ");
        }
        $obj = new $className;
        if(!($obj instanceof Interceptor_Handle)){
            throw new \Exception("$className should be instanceof Interceptor_Handle");
        }

        return $obj;
    }

    
}
