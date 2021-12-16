<?php

namespace NspTeam\WeiWork\Api;

use NspTeam\WeiWork\Exception\ParameterError;

/**
 * JsapiTicket
 * @package NspTeam\WeiWork\Api
 */
trait JsapiTicket
{
    /**
     * JsApiSignatureGet : 计算jsapi的签名
     * @link https://work.weixin.qq.com/api/doc#10029/%E7%AD%BE%E5%90%8D%E7%AE%97%E6%B3%95
     * @param string $jsapiTicket
     * @param string $nonceStr
     * @param int $timestamp
     * @param string $url
     * @return string
     */
    public function jsApiSignatureGet(string $jsapiTicket, string $nonceStr, int $timestamp, string $url): string
    {
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        return sha1($string);
    }

    /**
     * 获取企业的JSAPI_TICKET | 应用的JSAPI_TICKET
     * @param bool $needAgentId 是否需要应用的JSAPI_TICKET
     * @return null|string
     * @throws ParameterError
     * @throws \JsonException
     */
    public function getTicket(bool $needAgentId = false): ?string
    {
        if ($this->access_token === null) {
            throw new ParameterError('access_token参数为空');
        }

        if ($needAgentId && !empty($this->agentId)) {
            $url = self::DOMAIN . self::GET_AGENT_TICKET;
        } else {
            $url = self::DOMAIN . self::GET_JSAPI_TICKET;
        }
        $real_url = str_replace("ACCESS_TOKEN", $this->access_token, $url);

        $response = get_method($real_url);
        if (!empty($response['body'])) {
            $decode = json_decode($response['body'], false, 512, JSON_THROW_ON_ERROR);
            if ($decode->errcode === 0) {
                return $decode->ticket;
            }
        }
        return null;
    }
}