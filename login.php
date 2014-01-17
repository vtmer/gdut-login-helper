<?php

function array2post_form($arr) {
    $form = '';
    foreach ($arr as $key => $value) {
        $form .= urlencode($key) . '=' . urlencode($value) . '&';
    }
    return rtrim($form, '&');
}

class CURLException extends Exception {
    protected $message = 'CURL execute error';
}

class LoginException extends Exception {
    protected $message = 'Login failed';
}

class GetInfoException extends Exception {
    protected $message = 'Get student infomation failed';
}

/**
 * 登录抽象类
 */
abstract class AbstractLogin
{
    /**
     * 设置登录信息
     *
     * @param string 用户名
     * @param string 登录密码
     */
    abstract public function setup($username, $password);

    /**
     * 进行登录
     *
     * @throws `LoginException`
     * @throws `CURLException`
     */
    abstract public function login();

    /**
     * 获取用户信息
     *
     * @return array
     * @throws `GetInfoException`
     */
    abstract public function getInfos();

    /**
     * 模拟使用的 user-agent 值
     */
    protected $ua = 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)';

    /**
     * 请求超时设定 
     */
    protected $timeout = 10;

    /**
     * 发起一个 http 请求
     *
     * @param string 请求地址
     * @param array 请求表单，假如非空则使用 `POST` 请求
     * @param array 对 curl 的额外配置项
     * @return array 包含请求时长、响应头、响应内容
     * @throws `CURLException`
     */
    protected function request($url, $form = null, $extraOptions = array())
    {
        $starttime = microtime();
        
        // setup curl
        $conn = curl_init();
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_VERBOSE, 0);
        curl_setopt($conn, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, $this->ua);
        // follow 302
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        // HTTP redirect
        curl_setopt($conn, CURLOPT_MAXREDIRS, 7);
        curl_setopt($conn, CURLOPT_HEADER, 1);

        // carry post form
        if ($form) {
            if (is_array($form)) {
                $form = array2post_form($form);
            }
            curl_setopt($conn, CURLOPT_POST, 1);
            curl_setopt($conn, CURLOPT_POSTFIELDS, $form);
        }

        // extra options
        foreach ($extraOptions as $k => $v) {
            curl_setopt($conn, $k, $v);
        }

        // execute
        $content = curl_exec($conn);
        $duration = microtime() - $starttime;
        if ($content === false) {
            throw new CURLException(curl_error($conn));
        }

        // process response header
        $header = curl_getinfo($conn);

        curl_close($conn);
        return array(
            'duration' => $duration,
            'header' => $header,
            'body' => $content
        );
    }

    /**
     * 根据正则表达式解析信息
     *
     * @param string 正则表达式
     * @param string 内容
     * @return mixed
     */
    protected function parseInformation($pattern, $body)
    {
        if (!preg_match($pattern, $body, $val)) {
            return null;
        }
        return $val[1];
    }
}
