<?php

ini_set('display_errors', 'On');

require_once('eswis.php');

$user = new ESWISLogin('3112005816', '12345');
$user->login();
var_dump($user->get_info());
