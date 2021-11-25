<?php

namespace NspTeam\WeiWork;

use NspTeam\WeiWork\callback\ErrorCode;
use NspTeam\WeiWork\callback\Crypt;
use NspTeam\WeiWork\callback\XMLParse;

/**
 * @see https://work.weixin.qq.com/api/doc/10514
 */
class WorkWxCrypt
{
    /**
     * 可由企业任意填写,用于生成签名
     * @var string
     */
    protected $token;

    /**
     * 用于消息体的加密，是AES密钥的Base64编码。
     * @var string
     */
    protected $encodingAESKey;

    protected $receiveId;

    /**
     * @param string $token 开发者设置的token
     * @param string $encodingAESKey 开发者设置的EncodingAESKey
     * @param string $receiveId ReceiveId 在各个场景的含义不同：
     * 1、企业应用的回调，表示corpid
     * 2、第三方事件的回调，表示suiteid
     */
    public function __construct(string $token, string $encodingAESKey, string $receiveId)
    {
        $this->receiveId = $receiveId;
        $this->token = $token;
        $this->encodingAESKey = $encodingAESKey;
    }

    /**
     * @param string $msgSignature 企业微信加密签名，msg_signature结合了企业填写的token、请求中的timestamp、nonce参数、加密的消息体
     * @param int $timeStamp 时间戳
     * @param string $nonce 随机数
     * @param string $echoStr 加密的字符串。需要解密得到消息内容明文，解密后有random、msg_len、msg、CorpID四个字段，其中msg即为消息内容明文。注意，此参数必须是urldecode后的值
     * @param string $replyEchoStr 解密之后的echostr，当return返回0时有效
     * @return int 成功0，失败返回对应的错误码
     */
    public function verifyUrl(string $msgSignature, int $timeStamp, string $nonce, string $echoStr, string &$replyEchoStr): int
    {
        if (strlen($this->encodingAESKey) !== 43) {
            return ErrorCode::IllegalAesKey;
        }

        $arr = getSHA1($this->token, $timeStamp, $nonce, $echoStr);
        $ret = $arr[0];

        if ($ret !== 0) {
            return $ret;
        }

        $signature = $arr[1];
        if ($signature !== $msgSignature) {
            return ErrorCode::ValidateSignatureError;
        }

        $pc = new Crypt($this->encodingAESKey);
        $result = $pc->decrypt($echoStr, $this->receiveId);
        if ($result[0] !== 0) {
            return $result[0];
        }
        $replyEchoStr = $result[1];

        return ErrorCode::OK;
    }

    /**
     * 将公众平台回复用户的消息加密打包
     * <ol>
     *    <li>对要发送的消息进行AES-CBC加密</li>
     *    <li>生成安全签名</li>
     *    <li>将消息密文和安全签名打包成xml格式</li>
     * </ol>
     * @param string $sReplyMsg 公众平台待回复用户的消息，xml格式的字符串
     * @param mixed $sTimeStamp 时间戳，可以自己生成，也可以用URL参数的timestamp
     * @param string $sNonce 随机串，可以自己生成，也可以用URL参数的nonce
     * @param string $sEncryptMsg 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串, 当return返回0时有效
     * @return int 成功0，失败返回对应的错误码
     */
    public function encryptMsg(string $sReplyMsg, $sTimeStamp = null, string $sNonce, string &$sEncryptMsg): int
    {
        $pc = new Crypt($this->encodingAESKey);

        //加密
        $array = $pc->encrypt($sReplyMsg, $this->receiveId);
        $ret = $array[0];
        if ($ret !== 0) {
            return $ret;
        }

        if ($sTimeStamp === null) {
            $sTimeStamp = time();
        }
        $encrypt = $array[1];

        //生成安全签名
        $array = getSHA1($this->token, $sTimeStamp, $sNonce, $encrypt);
        $ret = $array[0];
        if ($ret !== 0) {
            return $ret;
        }
        $signature = $array[1];

        //生成发送的xml
        $parse = new XMLParse;
        $sEncryptMsg = $parse->generate($encrypt, $signature, $sTimeStamp, $sNonce);
        return ErrorCode::OK;
    }

    /**
     * 检验消息的真实性，并且获取解密后的明文
     * <ol>
     *    <li>利用收到的密文生成安全签名，进行签名验证</li>
     *    <li>若验证通过，则提取xml中的加密消息</li>
     *    <li>对消息进行解密</li>
     * </ol>
     * @param string $sMsgSignature 签名串，对应URL参数的msg_signature
     * @param mixed $sTimeStamp 时间戳 对应URL参数的timestamp
     * @param string $sNonce 随机串，对应URL参数的nonce
     * @param string $sPostData 密文，对应POST请求的数据
     * @param string $sMsg 解密后的原文，当return返回0时有效
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptMsg(string $sMsgSignature, $sTimeStamp = null, string $sNonce, string $sPostData, string &$sMsg): int
    {
        if (strlen($this->encodingAESKey) !== 43) {
            return ErrorCode::IllegalAesKey;
        }

        //提取密文
        $parse = new XMLParse;
        $array = $parse->extract($sPostData);
        $ret = $array[0];

        if ($ret !== 0) {
            return $ret;
        }

        if ($sTimeStamp === null) {
            $sTimeStamp = time();
        }

        $encrypt = $array[1];

        //验证安全签名
        $array = getSHA1($this->token, $sTimeStamp, $sNonce, $encrypt);
        $ret = $array[0];

        if ($ret !== 0) {
            return $ret;
        }

        $signature = $array[1];
        if ($signature !== $sMsgSignature) {
            return ErrorCode::ValidateSignatureError;
        }

        $pc = new Crypt($this->encodingAESKey);
        $result = $pc->decrypt($encrypt, $this->receiveId);
        if ($result[0] !== 0) {
            return $result[0];
        }
        $sMsg = $result[1];

        return ErrorCode::OK;
    }
}