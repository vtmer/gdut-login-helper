<?php

require_once('login.php');

class ESWISLogin extends LoginInterface {
    protected $login_url = 'http://eswis.gdut.edu.cn/default.aspx';

    private function get_session_form() {
        $ret = $this->request($this->login_url);
        $html = $ret['body'];

        $session = array(
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => ''
        );
        preg_match('/__EVENTVALIDATION" value="([^\"]*)/', $html, $val);
        $session['__EVENTVALIDATION'] = $val[1];
        preg_match('/__VIEWSTATE" value="([^\\"]*)/', $html, $val);
        $session['__VIEWSTATE'] = $val[1];
        preg_match('/__PREVIOUSPAGE" value="([^\\"]*)/', $html, $val);
        $session['__PREVIOUSPAGE'] = $val[1];

        return $session;
    }

    private function check_login($body) {
        if (preg_match('/ctl00_msg_logon class="msgstr">([^<]+)</',
            $body, $ret)) {
            return $ret[1];
        }
        return false;
    }

    private function get_session_id($body) {
        if (preg_match('/ASP.NET_SessionId=([0-9a-z]+)/', $body, $ret)) {
            return $ret[1];
        }
        return null;
    }

    public function login() {
        $session_form = $this->get_session_form();
        $session_form['ctl00$log_username'] = $this->username;
        $session_form['ctl00$log_password'] = $this->password;
        $session_form['ctl00$logon'] = '登录';

        $ret = $this->request($this->login_url, $session_form);
        $err = $this->check_login($ret['body']);
        if ($err) {
            throw new LoginException($err);
        }

        $session_id = $this->get_session_id($ret['body']);
        if (!$session_id) {
            throw new LoginException('Session ID not found');
        }
        return $session_id;
    }

    private function parse_information($re, $body) {
        if (!preg_match($re, $body, $val)) {
            throw new GetInfoException();
        }
        return $val[1];
    }

    public function get_info($session_id) {
        $url = 'http://eswis.gdut.edu.cn/opt_xxhz.aspx?key=' . $session_id;
        $ret = $this->request($url, null, $session_id);
        $body = $ret['body'];

        $info = array(
            'name' => $this->parse_information(
                '/ctl00_cph_right_inf_xm">([^<]+)>/', $body),
            'stu_id' => $this->parse_information(
                '/ctl00_cph_right_inf_xh">([^<]+)>/', $body)
        );
        $details = explode(' ', $this->parse_information(
            '/ctl00_cph_right_inf_dw">([^<]+)</', $body
        ));
        $info['campus'] = $details[0];
        $info['faculty'] = $details[1];
        $info['major'] = $details[2];
        $info['grade'] = $details[3];
        $info['class'] = $details[4];

        return $info;
    }
}
