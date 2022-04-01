<?php
/*
 * @Author: Brightness
 * @Date: 2022-04-01 16:13:28
 * @LastEditors: Brightness
 * @LastEditTime: 2022-04-01 16:13:28
 * @Description:  跨域扩展
*/
class Cors_Lib {

    protected $config = array(

        //域名白名单
        'whitelist' => array(),

        //header头
        'headers' => array()
    );

    protected $flag = false;
    
    public function __construct() {
        if(DI()->config->get('app.cors'))
            $this->config = array_merge($this->config, DI()->config->get('app.cors'));

        $origin = DI()->request->getHeader('Origin');

        foreach ($this->config['whitelist'] as $val) {
            if($origin == $val){
                $this->flag = true;
            }
        }

        if($this->flag){

            $this->config['headers']['Access-Control-Allow-Origin'] = $origin;
            $this->config['headers']['Access-Control-Allow-Headers'] = 'Content-Type';

            foreach ($this->config['headers'] as $key => $val) {
                 DI()->response->addHeaders($key,$val);
            }
        }
    }
}