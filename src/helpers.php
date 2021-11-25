<?php

use NspTeam\WeiWork\callback\ErrorCode;


/**
 * 用SHA1算法生成安全签名
 * @param string $token 票据
 * @param int $timestamp 时间戳
 * @param string $nonce 随机字符串
 * @param string $encrypt_msg 密文消息
 * @return array
 */
function getSHA1(string $token, int $timestamp, string $nonce, string $encrypt_msg): array
{
    try {
        $array = array($encrypt_msg, $token, $timestamp, $nonce);
        //sort的含义是将参数值按照字母字典排序，然后从小到大拼接成一个字符串
        sort($array, SORT_STRING);
        $str = implode('', $array);
        return array(ErrorCode::OK, sha1($str));
    } catch (Exception $e) {
        print $e . "\n";
        return array(ErrorCode::ComputeSignatureError, null);
    }
}


if (!function_exists('post_method')) {
    /**
     * @param string $url
     * @param array|object $body
     * @param array $headerArray
     * @param int $retry
     * @return array
     * @throws Exception
     */
    function post_method(string $url, $body, array $headerArray, int $retry = 1): array
    {
        $header = [];
        $formRequest = false;
        $jsonRequest = false;
        $xmlRequest = false;
        if (!empty($headerArray)) {
            foreach ($headerArray as $key => $value) {
                $header[] = "$key: $value";
                if (strpos($value, "application/json") !== false) {
                    $jsonRequest = true;
                } elseif (strpos($value, "x-www-form-urlencoded")) {
                    $formRequest = true;
                } elseif (strpos($value, "xml")) {
                    $xmlRequest = true;
                }
            }
        }

        $postFields = $body;
        if ($jsonRequest) {
            $postFields = json_encode($body, JSON_UNESCAPED_UNICODE);
        } elseif ($formRequest) {
            $postFields = http_build_query($body);
        }

        $url = str_replace(' ', '+', $url); //对空格进行转义
        $agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22';

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => $agent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,           // 头文件信息是否作为数据流输出
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            //CURLOPT_SSL_VERIFYPEER => false,    // 对认证证书来源的检查
            //CURLOPT_SSL_VERIFYHOST => 0,    // verify ssl   生产环境中，这个值应该是 2（默认值）
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        if (!empty($header)) {
            curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);    //追踪句柄的请求字符串
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);
        if ($errorCode > 0 && $retry > 1) {
            return post_method($url, $body, $headerArray, ++$retry);
        }
        $responseBody = array();
        return extracted($errorCode, $ch, $output, $responseBody);
    }
}

if (!function_exists('get_method')) {
    /**
     * @param string $url
     * @return array
     */
    function get_method(string $url): array
    {
        $responseBody = array();

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,           // 头文件信息是否作为数据流输出
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            //CURLOPT_SSL_VERIFYPEER => false,    // 对认证证书来源的检查
            //CURLOPT_SSL_VERIFYHOST => 0,    // verify ssl   生产环境中，这个值应该是 2（默认值）
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);

        return extracted($errorCode, $ch, $output, $responseBody);
    }
}

if (!function_exists('extracted')) {
    /**
     * @param int $errorCode
     * @param resource $ch
     * @param bool|string $output
     * @param array $responseBody
     * @return array
     */
    function extracted(int $errorCode, $ch, $output, array $responseBody): array
    {
        if ($errorCode <= 0) {
            // 头文件信息是否作为数据流输出
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header_text = substr($output, 0, $header_size);
            $bodyStr = substr($output, $header_size);
            $header = array();
            foreach (explode("\r\n", $header_text) as $i => $line) {
                if (!empty($line)) {
                    if ($i === 0) {
                        $header[0] = $line;
                    } else if (strpos($line, ": ")) {
                        [$key, $value] = explode(': ', $line);
                        $header[$key] = $value;
                    }
                }
            }
            $responseBody['headers'] = $header;
            $responseBody['body'] = $bodyStr;
            $responseBody['http_code'] = $httpCode;
        }
        curl_close($ch);

        return $responseBody;
    }
}