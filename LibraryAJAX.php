<?php

require_once 'login.php';


/**
 * 图书馆 ajax 登录接口
 */
class LibraryAJAX extends AbstractLogin
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
    private $loginUrl = 'http://222.200.98.171:81/internalloginAjax.aspx';

    /**
     * referer 地址
     */
    private $refererUrl = 'http://222.200.98.171:81/internal_login.aspx';

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
        $form = array(
            'username' => $this->username,
            'password' => $this->password
        );
        try {
            $ret = $this->request($this->loginUrl, $form, array(
                CURLOPT_REFERER => $this->refererUrl
            ));
        } catch (Exception $e) {
            throw new LoginException();
        }

        $this->cookie = parseInformation(
            '/sulcmiswebpac=([0-9a-zA-Z]+)/',
            $ret['body']
        );
        if (!$this->cookie) {
            throw new LoginException();
        }
    }

    /**
     * @return array(
     *      学生姓名
     *      学生学生卡卡号
     *      学院名称
     *      学生专业
     * )
     */
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
