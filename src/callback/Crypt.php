<?php

namespace NspTeam\WeiWork\callback;

use Exception;

/**
 * @see https://work.weixin.qq.com/api/doc/90000/90139/90968
 */
class Crypt
{
    /**
     * 解码后即为32字节长的AESKey
     * @var false|string
     */
    public $AESKey;

    /**
     * IV初始向量大小为16字节, 取AESKey前16字节
     * @var false|string
     */
    public $iv;

    /**
     * @param string $encodingAESKey 用于消息体的加密，长度固定为43个字符，从a-z, A-Z, 0-9共62个字符中选取，是AESKey的Base64编码
     */
    public function __construct(string $encodingAESKey)
    {
        $this->AESKey = base64_decode($encodingAESKey . '=');
        $this->iv = substr($this->AESKey, 0, 16);

    }

    /**
     * 加密
     * @param $text
     * @param $receiveId
     * @return array
     */
    public function encrypt($text, $receiveId): array
    {
        try {
            //拼接
            $text = $this->getRandomStr() . pack('N', strlen($text)) . $text . $receiveId;
            //添加PKCS#7填充
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            //加密
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->AESKey, OPENSSL_ZERO_PADDING, $this->iv);
            //$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($text), MCRYPT_MODE_CBC, $this->iv);
            return array(ErrorCode::OK, $encrypted);
        } catch (Exception $e) {
            print $e;
            return array(ErrorCode::EncryptAESError, null);
        }
    }

    /**
     * 解密
     * @param $encrypted
     * @param $receiveId
     * @return array
     */
    public function decrypt($encrypted, $receiveId): array
    {
        try {
            //解密
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->AESKey, OPENSSL_ZERO_PADDING, $this->iv);
        } catch (Exception $e) {
            return array(ErrorCode::DecryptAESError, null);
        }

        try {
            //删除PKCS#7填充
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            if (strlen($result) < 16) {
                return array();
            }
            //拆分
            $content = substr($result, 16, strlen($result));
            $msg_len = unpack('N', substr($content, 0, 4));
            $xml_len = $msg_len[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_receiveId = substr($content, $xml_len + 4);
        } catch (Exception $e) {
            print $e;
            return array(ErrorCode::IllegalBuffer, null);
        }

        if ($from_receiveId !== $receiveId) {
            return array(ErrorCode::ValidateCorpIdError, null);
        }
        return array(0, $xml_content);
    }

    /**
     * 生成随机字符串
     * @return string
     * @throws \Exception
     */
    private function getRandomStr(): string
    {
        $str = '';
        $str_pol = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyl';
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[random_int(0, $max)];
        }
        return $str;
    }
}