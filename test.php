<?php

ini_set('display_errors', 'On');

require_once('eswis.php');

$user = new ESWISLogin('3112006816', '12345');
$session_id = $user->login();
var_dump($user->get_info($session_id));
