<?php

require_once 'bootstrap.php';
require_once __DIR__ . '/../ESWIS.php';


class ESWISTest extends Login_Helper_TestCase
{
    public function setup()
    {
        parent::setup();

        $this->helper = new ESWIS();
    }
    
    public function testLoginException()
    {
        $this->setExpectedException('LoginException');
        $this->helper->setup('3112005816', 'not_my_password');
        $this->helper->login();
    }

    public function testGetInfos()
    {
        $cred = $this->infos['credential']['eswis'];
        $this->helper->setup($cred['username'], $cred['password']);
        $this->helper->login();
        $infos = $this->helper->getInfos();

        $this->assertEquals($infos['student_number'],
            $this->infos['student_number']);
        $this->assertEquals($infos['student_name'],
            $this->infos['student_name']);
        $this->assertEquals($infos['faculty'], $this->infos['faculty']);
        $this->assertEquals($infos['major'], $this->infos['major']);
    }
}
