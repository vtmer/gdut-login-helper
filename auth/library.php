<?php

require_once 'utils.php';
require_once '../Library.php';


if (!isAuth()) {
    showUsage();
}

$infos = input();
output(login(Library, $infos['username'], $infos['password']));
