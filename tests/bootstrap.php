<?php

require_once __DIR__ . '/../vendor/autoload.php';


class Login_Helper_TestCase extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->infos = include('informations.php');
    }
}
