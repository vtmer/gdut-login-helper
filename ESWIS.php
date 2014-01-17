<?php

require_once 'login.php';


/**
 * 学生工作信息管理系统登录接口
 *
 * 原始代码来自：http://t.cn/zQx3h1v
 */
class ESWIS extends AbstractLogin
{
    /**
     * 学生证号码
     */
    private $username = null;

    /**
     * 学生工作信息系统登录密码
     */
    private $password = null;

    /**
     * 登录地址
     */
    private $loginUrl = 'http://eswis.gdut.edu.cn/default.aspx';

    /**
     * 学生信息地址
     */
    private $infoUrl = 'http://eswis.gdut.edu.cn/opt_xxhz.aspx?key=';

    /**
     * Session ID
     */
    private $sessionId = null;

    /**
     * Session key
     */
    private $key = null;

    public function setup($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function login()
    {
        $ret = $this->request($this->loginUrl);
        $form = getASPSessionForm($ret['body']);
        $form['ctl00$log_username'] = $this->username;
        $form['ctl00$log_password'] = $this->password;
        $form['ctl00$logon'] = '登录';

        $ret = $this->request($this->loginUrl, $form);

        $this->sessionId = parseInformation(
            '/ASP.NET_SessionId=([0-9a-z]+)/',
            $ret['body']
        );
        if (!$this->sessionId) {
            throw new LoginException('Session ID not found.');
        }

        $this->key = parseInformation(
            '/\?key=([0-9a-zA-Z]+)/',
            $ret['body']
        );
        if (!$this->key) {
            throw new LoginException('Key not found.');
        }
    }

    public function getInfos()
    {
        if (!$this->key || !$this->sessionId) {
            throw new GetInfoException();
        }

        $url = $this->infoUrl . $this->key;
        $ret = $this->request($url, null, array(
            CURLOPT_COOKIE => 'ASP.NET_SessionId=' . $this->sessionId
        ));
        $body = $ret['body'];

        $infos = array(
            'student_name' => parseInformation(
                '/ctl00_cph_right_inf_xm">([^<]+)</', $body
            ),
            'student_number' => parseInformation(
                '/ctl00_cph_right_inf_xh">([^<]+)</', $body
            )
        );
        $details = explode(' ', parseInformation(
            '/ctl00_cph_right_inf_dw">([^<]+)</', $body
        ));
        $infos['campus'] = $details[0];
        $infos['faculty'] = $details[1];
        // 去掉多余的专业两个字
        $infos['major'] = mb_substr($details[2], 0, -6);
        $infos['grade'] = $details[3];
        $infos['class'] = $details[4];

        return $infos;
    }
}
