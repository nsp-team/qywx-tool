<?php

namespace NspTeam\WeiWork\Api;

use ParseError;

trait Message
{
    /**
     * 发送文本消息
     * @param string $text
     * @param string $to_user 成员ID列表（多个接收者用‘|’分隔，最多支持1000个
     * @param string $to_party 部门ID列表，多个接收者用‘|’分隔，最多支持100个。
     * @return array
     * @throws \Exception
     */
    public function sendTextMessage(string $text, string $to_user = '@all', string $to_party = ''): array
    {
        if (!$this->access_token) {
            throw new ParseError('请先提供access_token.');
        }

        $url = self::DOMAIN . self::MESSAGE_SEND;
        $real_url = str_replace("ACCESS_TOKEN", $this->access_token, $url);
        $data = new \stdClass();
        $data->touser = $to_user;
        if ($to_user !== '@all') {
            $data->toparty = $to_party;
        }
        $data->agentid = $this->agentId;
        $data->msgtype = "text";
        $data->text = ["content" => $text];
        $data->safe = 1;
        $data->enable_duplicate_check = 1;
        $data->duplicate_check_interval = 600;

        return post_method($real_url, $data, ['Content-Type' => 'application/json']);
    }

}