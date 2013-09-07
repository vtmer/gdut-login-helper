<?php

require_once('login.php');

class JWGLogin extends LoginInterface {
    protected $login_url = 'http://jwgl.gdut.edu.cn/default2.aspx';
    private $checkcode_url = 'http://jwgl.gdut.edu.cn/CheckCode.aspx';

    public function get_viewstate() {
        $ret = $this->request($this->login_url);
        $body = $ret['body'];

        $pattern =
            '/<input[^>]+[nameid]=\"__VIEWSTATE\"\svalue=\"([^\"]+)\"[^>]+>/';

        if (preg_match($pattern, $body, $ret)) {
            return $ret[1];
        }

        return null;
    }

    public function get_code() {
        return '<img src="' . $this->checkcode_url . '" />';
    }

    private function set_session_form($username, $password, $code, $viewstate) {
        $session = array(
            'TextBox1' => $username,
            'TextBox2' => $password,
            'TextBox3' => $code,
            'RadioButtonList1' => '\xd1\xa7\xc9\xfa',
            '__VIEWSTATE' => $viewstate
        );

        return $session;
    }

    public function _login($username, $password, $code, $viewstate) {
        $session_form = $this->set_session_form($username, $password, $code,
                                                $viewstate);

        var_dump($session_form);
        $ret = $this->request($this->login_url, $session_form);
        var_dump($ret);
    }

    public function __construct() {}
}
