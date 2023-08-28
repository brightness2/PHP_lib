<?php
namespace app\common\library;

class JWT{

    private  $header = [
        'alg'  => 'HS256',    // 声明加密的算法 HMAC SHA256
        'typ'  => 'JWT',        // 声明类型
    ];

    private $secret = "hoeron";
    public $errMsg = '';
    public $expire = 7200;


    public function __construct($expire=7200,$secret='hoeron')
    {
        if(is_int($expire)){
            $this->expire = $expire;
        }
        if(is_string($secret)){
            $this->secret = $secret;            
        }

    }

    public function getToken($payload)
    {
        if(!is_array($payload)){
            $this->_setError('payload must be a Array');
            return false;
        }
        $base_header = base64_encode(json_encode($this->header));
        $payload['iat'] = time();
        $payload['exp'] = $this->expire==0?0:($payload['iat'] + $this->expire);
        $base_payload = base64_encode(json_encode($payload));
        $sign = $this->signature($base_header.$base_payload,$this->secret);
        return $base_header.'.'.$base_payload.'.'.$sign;
    }

    /**
     * jwt签名
     *
     * @param string $input
     * @param string $secret
     * @param string $alg
     * @return string
     */
    public function signature($input,$secret,$alg='HS256')
    {
       return md5($input.$secret.$secret.$alg);
    }

    public function verifyToken($token)
    {
        $arr = explode('.',$token);
        if(count($arr) != 3){
            $this->_setError('toke格式错误');
            return false;
        }
        list($header,$payload,$sign) = $arr;
        if($sign != $this->signature($header.$payload,$this->secret)){
            $this->_setError('签名错误');
            return false;
        }
        $header = json_decode(base64_decode($header),true);
        if(empty($header['alg'])){
            $this->_setError('token头部错误');
            return false;
        }
        if( $header['alg'] != $this->header['alg']){
            $this->_setError('token头部错误');
            return false;
        }
        $payload = json_decode(base64_decode($payload),true);
        if(empty($payload['iat'])){
            $this->_setError('token载荷错误');
            return false;
        }
        if($payload['iat'] > time()){
            $this->_setError('签发时间大于当前服务器时间');
            return false;
        }
        if(empty($payload['exp'])){
            $this->_setError('token载荷错误');
            return false;
        }
        if($payload['exp']!=0&&$payload['exp']<time()){
            $this->_setError('签名过期');
            return false;
        }
        return $payload;
        
    }

    public function getError()
    {
        return $this->errMsg;
    }

    private function _setError($msg)
    {
        $this->errMsg = $msg;
    }



}