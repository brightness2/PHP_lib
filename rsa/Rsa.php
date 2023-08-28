<?php

namespace app\common\domain\rsa;

/**
 * RSA签名类
 */
class Rsa
{
    public $publicKey = '';
    public $privateKey = '';
    private $_pubKey;
    private $_privKey;

    /**
     * 
     * @param string $publicKey  公钥 可以是文件路径，也可以是秘钥字符串
     * @param string $privateKey 私钥 可以是文件路径，也可以是秘钥字符串
     */
    public function __construct($publicKey = null, $privateKey = null)
    {
        $publicKey = $publicKey?$publicKey:__DIR__.'/pub.key';
        $privateKey = $privateKey?$privateKey:__DIR__.'/pri.key';

        $this->setKey($publicKey, $privateKey);
    }

    /**
     * 设置秘钥
     *
     * @param string $publicKey  公钥 可以是文件路径，也可以是秘钥字符串
     * @param string $privateKey 私钥 可以是文件路径，也可以是秘钥字符串
     * @return void
     */
    public function setkey($publicKey,$privateKey)
    {
        if (!is_null($publicKey)) {
            $this->publicKey = $publicKey;
            $this->setupPubKey();
        }
        if (!is_null($privateKey)) {
            $this->privateKey = $privateKey;
            $this->setupPrivKey();
        }
    }

    private function setupPubKey()
    {
        if(is_file($this->publicKey)){
            $this->_pubKey = openssl_pkey_get_public(file_get_contents($this->publicKey));
        }else{
            $pem = chunk_split($this->publicKey, 64, "\n");
            $pem = "-----BEGIN PRIVATE KEY-----\n" . $pem . "-----END PRIVATE KEY-----\n";
            $this->_pubKey = openssl_pkey_get_public($pem);
        }
        return true;
    }

    private function setupPrivKey()
    {
        if(is_file($this->privateKey)){
            $this->_privKey = openssl_pkey_get_private(file_get_contents($this->privateKey));
        }else{
            $pem = chunk_split($this->privateKey, 64, "\n");
            $pem = "-----BEGIN PRIVATE KEY-----\n" . $pem . "-----END PRIVATE KEY-----\n";
            $this->_privKey = openssl_pkey_get_private($pem);
        }
        return true;
    }
    /**
     * 公钥加密
     *
     * @param string $data
     * @return string/null
     */
    public function pubEncrypt($data)
    {
        if (!is_string($data)) {
            return null;
        }
        $r = openssl_public_encrypt($data, $encrypted, $this->_pubKey);
        if ($r) {
            return base64_encode($encrypted);
        }
        return null;
    }

    /**
     * 公钥解密
     *
     * @param string $crypted
     * @return string/null
     */
    public function pubDecrypt($crypted)
    {
        if (!is_string($crypted)) {
            return null;
        }
        $crypted = base64_decode($crypted);
        $r = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
        if ($r) {
            return $decrypted;
        }
        return null;
    }

    /**
     * 私钥加密
     *
     * @param string $data
     * @return string/null
     */
    public function privEncrypt($data)
    {
        if (!is_string($data)) {
            return null;
        }
        $r = openssl_private_encrypt($data, $encrypted, $this->_privKey);
        if ($r) {
            return base64_encode($encrypted);
        }
        return null;
    }

    /**
     * 私钥解密
     *
     * @param string $encrypted
     * @return string/null
     */
    public function privDecrypt($encrypted)
    {
        if (!is_string($encrypted)) {
            return null;
        }
        $encrypted = base64_decode($encrypted);
        $r = openssl_private_decrypt($encrypted, $decrypted, $this->_privKey);
        if ($r) {
            return $decrypted;
        }
        return null;
    }

    /**
     * 构造签名
     * @param string $dataString 被签名数据
     * @return string/bool
     */
    public function sign($dataString)
    {
        $signature = false;
        openssl_sign($dataString, $signature, $this->_privKey);
        return base64_encode($signature);
    }

    /**
     * 验证签名
     *
     * @param string $dataString
     * @param string $signString
     * @return int 1签名正确 0签名错误
     */
    public function verify($dataString, $signString)
    {
        $signature = base64_decode($signString);
        $flg = openssl_verify($dataString, $signature, $this->_pubKey);
        return $flg;
    }

    function __destruct()
    {
        is_resource($this->_privKey) && @openssl_free_key($this->_privKey);
        is_resource($this->_pubKey) && @openssl_free_key($this->_pubKey);
    }
}
