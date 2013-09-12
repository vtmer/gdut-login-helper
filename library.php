<?php
 require('login.php');

 class LibrayLogin extends LoginInterface
 {
 	protected $login_url = 'http://222.200.98.171:81/login.aspx';

 	private  function get_session_form() 
 	{
 		$ret = $this->request($this->login_url);
        $html = $ret['body'];

        $session = array(
        		'__EVENTTARGET'=>'',
        		'__EVENTARGUMENT'=>''
        	);
        preg_match('/__EVENTVALIDATION" value="([^\"]*)/', $html, $val);
        $session['__EVENTVALIDATION'] = $val[1];
        preg_match('/__VIEWSTATE" value="([^\\"]*)/', $html, $val);
        $session['__VIEWSTATE'] = $val[1];

        return $session;
 	}
 	public function login()
 	{
 		$session_form = $this->get_session_form();
        $session_form['ctl00$ContentPlaceHolder1$txtUsername_Lib'] = $this->username;
        $session_form['ctl00$ContentPlaceHolder1$txtPas_Lib'] = $this->password;
        $session_form['ctl00$ContentPlaceHolder1$txtlogintype'] = 0;
        $session_form['ctl00$ContentPlaceHolder1$btnLogin_Lib'] ='登录';

        $ret = $this->request($this->login_url, $session_form);

        print_r($ret['body']);
 	}
 }