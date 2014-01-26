<?php

require_once 'utils.php';
require_once '../ESWIS.php';


if (!isAuth()) {
    showUsage();
}

$infos = input();
output(login(ESWIS, $infos['username'], $infos['password']));
