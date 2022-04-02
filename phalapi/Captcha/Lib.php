<?php
/*
 * @Author: Brightness
 * @Date: 2022-04-02 08:42:47
 * @LastEditors: Brightness
 * @LastEditTime: 2022-04-02 08:42:48
 * @Description:  图片验证码
*/

class Captcha_Lib{
    
    //默认配置
    protected $CaptchaConfig = [
        //验证码位数
        'length'   => 5,
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码过期时间
        'expire'   => 1800,
        // 是否使用中文验证码
        'useZh'    => false,
        // 是否使用算术验证码
        'math'     => false,
        // 是否使用背景图
        'useImgBg' => false,
        //验证码字符大小
        'fontSize' => 25,
        // 是否使用混淆曲线
        'useCurve' => true,
        //是否添加杂点
        'useNoise' => true,
        // 验证码字体 不设置则随机
        'fontttf'  => '',
        //背景颜色
        'bg'       => [243, 251, 254],
        // 验证码图片高度
        'imageH'   => 0,
        // 验证码图片宽度
        'imageW'   => 0,
    
        // 添加额外的验证码设置
        // verify => [
        //     'length'=>4,
        //    ...
        //],
    ];

    /**
     * 构造函数
     * 读取配置文件，合并配置
     */
    function __construct()
    {
        $config = $this->loadConfig();
        $this->captcha =  new Captcha_Base($config);
    }

    
    /**
     * 创建验证码,并直接输出图片
     *
     * @return void
     */
    public function create()
    {
        $this->captcha->create();
    }

    /**
     * 校验验证码
     *
     * @param string $code
     * @return bool
     */
    public function check($code)
    {
        return $this->captcha->check($code);
    }

    /**
     * 加载额外配置
     * config.php app.captcha
     * @return void
     */
    protected function loadConfig()
    {
        $config = DI()->config->get('app.captcha');
        if($config && is_array($config)){
            $this->CaptchaConfig = array_merge($this->CaptchaConfig,$config);
        }
        return $this->CaptchaConfig;
    }

}
