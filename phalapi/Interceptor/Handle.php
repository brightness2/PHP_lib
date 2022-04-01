<?php
/*
 * @Author: Brightness
 * @Date: 2022-03-31 14:16:16
 * @LastEditors: Brightness
 * @LastEditTime: 2022-03-31 14:16:16
 * @Description:  拦截器接口
*/
interface Interceptor_Handle{

    public function handle(PhalApi_Api $api,$data=null);
}