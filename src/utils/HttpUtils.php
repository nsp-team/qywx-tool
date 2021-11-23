<?php

namespace NspTeam\Qywx;

class HttpUtils
{
    public static function makeUrl(string $queryArgs): string
    {
        $base = "https://qyapi.weixin.qq.com";
        if ($queryArgs[0] === "/") {
            return $base . $queryArgs;
        }
        return $base . "/" . $queryArgs;
    }

    public static function array2Json($arr): string
    {
        $parts = array();
        $is_list = false;
        $keys = array_keys($arr);
        $max_length = count($arr) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length)) {
            $is_list = true;
            foreach ($keys as $i => $iValue) {
                if ($i !== $iValue) {
                    $is_list = false;
                    break;
                }
            }
        }

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                if ($is_list) {
                    $parts [] = self::array2Json($value);
                } else {
                    $parts [] = '"' . $key . '":' . self::array2Json($value);
                }
            } else {
                $str = '';
                if (!$is_list) {
                    $str = '"' . $key . '":';
                }
                if (!is_string($value) && is_numeric($value) && $value < 2000000000) {
                    $str .= $value;
                } elseif ($value === false) {
                    $str .= 'false';
                } elseif ($value === true) {
                    $str .= 'true';
                } else {
                    $str .= '"' . addcslashes($value, "\\\"\n\r\t/") . '"';
                }
                $parts[] = $str;
            }
        }
        $json = implode(',', $parts);
        if ($is_list) {
            return '[' . $json . ']';
        }
        return '{' . $json . '}';
    }

    /**
     * http get
     * @param string $url
     * @param bool $debug
     * @return bool|string
     * @throws \Exception
     */
    public static function httpGet(string $url, bool $debug = true)
    {
        if ($debug) {
            echo $url . "\n";
        }

        self::_checkDeps();
        $ch = curl_init();

        self::_setSSLOpts($ch, $url);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return self::_exec($ch);
    }

    /**
     * http post
     * @param string $url
     * @param $postData
     * @param bool $debug
     * @return bool|string
     * @throws \Exception
     */
    public static function httpPost(string $url, $postData, bool $debug = true)
    {
        if ($debug) {
            echo $url . " -d $postData\n";
        }

        self::_checkDeps();
        $ch = curl_init();

        self::_setSSLOpts($ch, $url);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        return self::_exec($ch);
    }

    /**
     * @param $ch
     * @param string $url
     */
    private static function _setSSLOpts($ch, string $url)
    {
        if (stripos($url,"https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
    }

    /**
     * @throws \Exception
     */
    private static function _checkDeps()
    {
        if (!function_exists("curl_init")) {
            throw new \Exception("missing curl extend");
        }
    }

    /**
     * @param $ch
     * @return bool|string
     * @throws \Exception
     */
    private static function _exec($ch)
    {
        $output = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);

        if ($output === false) {
            throw new \Exception("network error");
        }

        if ((int)$status["http_code"] !== 200) {
            throw new \Exception("unexpected http code " . $status["http_code"]);
        }

        return $output;
    }
}