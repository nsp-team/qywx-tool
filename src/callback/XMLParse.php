<?php

namespace NspTeam\WeiWork\callback;

use DOMDocument;
use Exception;

/**
 * XMLParse
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class XMLParse
{
    /**
     * 提取出xml数据包中的加密消息
     * @param string $xmlText 待提取的xml字符串
     * @return array 提取出的加密消息字符串
     */
    public function extract(string $xmlText): array
    {
        try {
            $xml = new DOMDocument();
            $xml->loadXML($xmlText);
            $array_e = $xml->getElementsByTagName('Encrypt');
            $encrypt = $array_e->item(0)->nodeValue;
            return array(0, $encrypt);
        } catch (Exception $e) {
            print $e . "\n";
            return array(ErrorCode::ParseXmlError, null);
        }
    }

    /**
     * 生成xml消息
     * @param string $encrypt 加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @return string
     */
    public function generate(string $encrypt, string $signature, string $timestamp, string $nonce): string
    {
        $format = <<<xml
<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>
xml;
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

}