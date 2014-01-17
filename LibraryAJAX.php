<?php

require_once 'Library.php';


/**
 * 图书馆 ajax 登录接口
 */
class LibraryAJAX extends Library
{
    /**
     * 登录地址
     */
    protected $loginUrl = 'http://222.200.98.171:81/internalloginAjax.aspx';

    /**
     * referer 地址
     */
    protected $refererUrl = 'http://222.200.98.171:81/internal_login.aspx';

    /**
     * 登录 session cookie
     */
    protected $cookie = null;

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
}
