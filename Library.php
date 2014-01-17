<?php

require_once 'login.php';

/**
 * 图书馆系统登录接口
 */
class Library extends AbstractLogin
{
    /**
     * 学生证号码
     */
    private $username = null;

    /**
     * 图书馆系统登录密码
     */
    private $password = null;

    /**
     * 登录地址
     */
    private $loginUrl = 'http://222.200.98.171:81/login.aspx';
    
    /**
     * 学生信息页地址
     */
    private $infoUrl = 'http://222.200.98.171:81/user/userinfo.aspx';

    /**
     * 登录 session cookie
     */
    private $cookie = null;

    public function setup($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function login()
    {
        $ret = $this->request($this->loginUrl);
        $form = getASPSessionForm($ret['body']);
        $form['ctl00$ContentPlaceHolder1$txtUsername_Lib'] = $this->username;
        $form['ctl00$ContentPlaceHolder1$txtPas_Lib'] = $this->password;
        $form['ctl00$ContentPlaceHolder1$txtlogintype'] = 0;
        $form['ctl00$ContentPlaceHolder1$btnLogin_Lib'] ='登录';

        $ret = $this->request($this->loginUrl, $form);
        $login_error = parseInformation(
            '/ctl00_ContentPlaceHolder1_lblErr_Lib"><font color="#ff0000">([^<]+)</',
            $ret['body']
        );
        if ($login_error) {
            throw new LoginException($login_error);
        }
        
        $this->cookie = parseInformation(
            '/sulcmiswebpac=([0-9a-zA-Z]+)/',
            $ret['body']
        );
        if (!$this->cookie) {
            throw new LoginException();
        }
    }

    public function getInfos()
    {
        if (!$this->cookie) {
            throw new GetInfoException();
        }

        $ret = $this->request($this->infoUrl, null, array(
            CURLOPT_COOKIE => 'sulcmiswebpac=' . $this->cookie
        ));
    
        preg_match_all(
            '/class="inforight">([^<]+)</', 
            $ret['body'],
            $val
        );
        $info['student_number'] = trim($val[1][0]);
        $info['student_name'] = trim($val[1][1]);
        $info['faculty'] = trim($val[1][3]);
        $info['major'] = trim($val[1][7]);

        return $info;
    }
}
