<?php

namespace NspTeam\WeiWork;

use NspTeam\WeiWork\api\Approval;
use NspTeam\WeiWork\api\Message;
use NspTeam\WeiWork\exception\ParameterError;
use NspTeam\WeiWork\exception\QyApiError;

class CorpAPI implements Api
{
    use Approval;

    use Message;

    /**
     * 企业微信应用密钥
     * @var mixed
     */
    protected $corpSecret;

    /**
     * 企业微信公司ID
     * @var mixed
     */
    protected $corpId;

    /**
     * 企业微信应用ID
     * @var mixed
     */
    protected $agentId;

    /**
     * 企业微信access_token
     * @var string 512字节
     */
    public $access_token;

    protected const DOMAIN = 'https://qyapi.weixin.qq.com';

    /**
     * 企业进行自定义开发调用, 请传参 corpid + secret, 不用关心accesstoken，本类会自动获取并刷新
     * @param string $corpId
     * @param string $secret
     * @param string|null $agentId
     * @throws ParameterError
     */
    public function __construct(string $corpId, string $secret, string $agentId = null)
    {
        if (empty($corpId) || empty($secret)) {
            throw new ParameterError("invalid corpid or secret");
        }
        $this->corpSecret = $secret;
        $this->corpId = $corpId;
        $this->agentId = $agentId;
    }

    /**
     * 获取access-token，并由应用自行保存token
     * @return string|null
     * @throws QyApiError
     */
    public function getAccessToken(): ?string
    {
        if (empty($this->access_token)) {
            $this->refreshAccessToken();
        }
        return $this->access_token;
    }

    /**
     * @throws QyApiError
     */
    protected function refreshAccessToken(): void
    {
        $url = self::DOMAIN . "/cgi-bin/gettoken?corpid=" . urlencode($this->corpId) . "&corpsecret=" . urlencode($this->corpSecret);
        $raw_str = file_get_contents($url);
        if (empty($raw_str)) {
            throw new QyApiError("empty response");
        }

        $raw_array = json_decode($raw_str, true);
        if (isset($raw_array['errcode'], $raw_array['access_token'])) {
            $err_code = $raw_array['errcode'];
            if ($err_code !== 0) {
                throw new QyApiError($raw_array['errmsg']);
            }
            $this->access_token = $raw_array['access_token'];
        }
    }

    /**
     * JsApiSignatureGet : 计算jsapi的签名
     * @link https://work.weixin.qq.com/api/doc#10029/%E7%AD%BE%E5%90%8D%E7%AE%97%E6%B3%95
     * @param string $jsapiTicket
     * @param string $nonceStr
     * @param string $timestamp
     * @param string $url
     * @return string
     */
    public function jsApiSignatureGet(string $jsapiTicket, string $nonceStr, string $timestamp, string $url): string
    {
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        return sha1($string);
    }
}