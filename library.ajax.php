<?php
require_once('login.php');

class LibraryAjaxLogin extends LoginInterface {
    protected $login_url = 'http://222.200.98.171:81/internalloginAjax.aspx';
    protected $referer_url = 'http://222.200.98.171:81/internal_login.aspx';
    protected $cookie;


    public function login($username, $password) {
        $form['username'] = $username;
        $form['password'] = $password;
        $ret = $this->request($this->login_url, $form, false, array(
            CURLOPT_REFERER => $this->referer_url
        ));
        $this->cookie = $this->parse_information(
            '/sulcmiswebpac=([0-9a-zA-Z]+)/',
            $ret['body']
        );

        return $this->get_info();
    }

    public function get_info() {
        $url = "http://222.200.98.171:81/user/userinfo.aspx";
        $ret = $this->request($url, null, false, array(
            CURLOPT_COOKIE => 'sulcmiswebpac=' . $this->cookie
        ));

        preg_match_all(
            '/class="inforight">([^<]+)</', 
            $ret['body'],
            $val
        );
        $info['student_number'] = $val[1][0];
        $info['student_name'] = $val[1][1];
        $info['faculty'] = $val[1][3];
        $info['major'] = $val[1][7];

        return $info;
    }

    public function __construct() {}
}
