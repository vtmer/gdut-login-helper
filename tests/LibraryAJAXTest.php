<?php

require_once 'bootstrap.php';
require_once __DIR__ . '/../library.ajax.php';

class LibraryAJAXTest extends Login_Helper_TestCase
{
    public function testGetInfoException()
    {
        $this->setExpectedException('GetInfoException');
        $helper = new LibraryAjaxLogin('3112005816', 'not_my_password');
        $helper->login();
    }

    public function testGetInfos()
    {
        $cred = $this->infos['credential']['library'];
        $helper = new LibraryAjaxLogin($cred['username'], $cred['password']);
        $infos = $helper->login();

        $this->assertEquals($infos['student_number'],
            $this->infos['student_number']);
        $this->assertEquals($infos['student_name'],
            $this->infos['student_name']);
        $this->assertEquals($infos['faculty'], $this->infos['faculty']);
        $this->assertEquals($infos['major'], $this->infos['major']);
    }
}
