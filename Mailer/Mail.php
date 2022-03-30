<?php
/*
 * @Author: Brightness
 * @Date: 2022-03-30 15:36:52
 * @LastEditors: Brightness
 * @LastEditTime: 2022-03-30 15:36:52
 * @Description:  
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once './PHPMailer/PHPMailer/Exception.php';
require_once './PHPMailer/PHPMailer/PHPMailer.php';
require_once './PHPMailer/PHPMailer/SMTP.php';

class Mailer
{
    protected $mail;#PHPMailer 类
    public $debug = 1;#开启调试
    protected $SMTPAuth = true;#开启smtp认证，必须
    public $isHTML = true;#邮件内容，使用html标签编写
    public $cache_key_prefix = 'mailTo';#
    public function __construct($from='',$debug=false,$config=array())
    {
      if(isset($config['cache_key_prefix'])) $this->cache_key_prefix = $config['cache_key_prefix'];
      $this->mail = new PHPMailer();
      $this->init($from,$debug,$config);
    }
    /**
     * 发送验证码
     *
     * @param string $mail
     * @param string $subject
     * @param string $from
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function sendMailCode($mail,$subject='',$from='')
    {
      //校验手机号码有效性
    //   $domain_validate = new Domain_Validate;
    //   $bool_valid = $domain_validate->check(['email'=>$mail],['email'=>'email'],['email'=>['email'=>'邮箱格式错误']]);
    //   if($bool_valid !==true) throw new MTS_ZException($bool_valid);
      
      $code = $this->createCode();
  
      $bool_cache = $this->cacheMailCode($this->cache_key_prefix.$mail,$code);
      if(!$bool_cache) throw new Exception('验证码获取失败');
      return $code;#临时输出
  
      if(!$subject) $subject = DI()->config->get('app.mail.defaultSubject');
      $content = '<h2>监控在线询价:'.$code.'</h2>';
    //   if(!$from) $from = DI()->config->get('app.mail.defaultFrom');
      
      $this->addToAddress($mail);#添加收件邮箱
      $res = $this->send($from,$subject,$content);
      return $res;
    }
  
    /**
     * 发送邮件,通知监控询价
     *
     * @param array $mail_arr
     * @param string $subject
     * @param string $from
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function sendMail($mail_arr,$content='',$subject='',$fromName='')
    {
          //校验手机号码有效性
        // $domain_validate = new Domain_Validate;
        // foreach($mail_arr as $mail){
        //   $bool_valid = $domain_validate->check(['email'=>$mail],['email'=>'email'],['email'=>['email'=>'邮箱格式错误']]);
        //   if($bool_valid !==true) {
        //     DI()->logger->info('Domain_Mail.sendMail 邮箱格式错误,',$mail);
        //     continue;
        //   }
        //   $this->addToAddress($mail);#添加收件邮箱
        // }
        // if($subject == '') $subject = DI()->config->get('app.mail.defaultSubject');
        // if($fromName == '') $subject = DI()->config->get('app.mail.defaultFromName');
        $res = $this->send($fromName,$subject,$content);
        return $res;
    }
    /**
     * 发送邮件
     *
     * @param string $fromName  发件人
     * @param string $subject   邮件主题
     * @param string $html      邮件内容 html (<h1>Hello World</h1>)
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function send($fromName,$subject,$html)
    {
      $this->mail->FromName = $fromName;
      $this->mail->Subject = $subject;
      $this->mail->Body = $html;
  
      $status = $this->mail->send();
      if($status){
        return true;
      }else{
        // DI()->logger->info('Domain_Mail send error:', $this->mail->ErrorInfo);
        return false;
      }
    }
    /**
     * 添加要发送的邮箱
     *
     * @param string $to 邮箱账号
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function addToAddress($to)
    {
      $this->mail->addAddress($to);
    }
     /**
    * 缓存验证码
    *
    * @param string $key
    * @param string $code
    * @return void
    * @desc checkMobileCode 时检验
    * @example
    * @author Brightness
    * @since
    */
    protected function cacheMailCode($key,$code,$time=180)
    {
        //   $domain_cache = new Tool_Cache(DI()->config->get('app.cache.default_dir').'mail'.D_S);
        //   $res = $domain_cache->set($code,$key,$time);
        //   if(!$res){
        //     DI()->logger->debug('Domain_Sms cacheMobileCode error:','cache code error');
        //   }
        //   return $res;
        return true;
    }
  
      /**
     * 检测验证吗是否存在
     *
     * @param string $key
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    public function checkMailCode($key='')
    {
        //   $domain_cache = new Tool_Cache(DI()->config->get('app.cache.default_dir').'mail'.D_S);
        //   $res = $domain_cache->get($key);
        //   return $res;
        return true;
    }
    /**
     * 生成验证码
     *
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    protected function createCode()
    {
      $verifyCode = substr(mt_rand(1000000,10000000),-6,6);
      return $verifyCode;
    }
    
    /**
     * PHPMailer 初始化
     *
     * @param string $from
     * @return void
     * @desc
     * @example
     * @author Brightness
     * @since
     */
    protected function init($from='',$debug=false,$config=array())
    {
      if($debug) $this->mail->SMTPDebug = $this->debug;
      $this->mail->isSMTP();
      $this->mail->SMTPAuth = $this->SMTPAuth;
      $this->mail->Host = $config['host'];
      $this->mail->SMTPSecure = $config['SMTPSecure'];
      $this->mail->Port =  $config['port'];
      $this->mail->CharSet = $config['charSet'];
      $this->mail->Username =  $config['user'];
      $this->mail->Password =  $config['pass'];
      $this->mail->From = $from;
      $this->mail->isHTML($this->isHTML);
    }
  
  
}
