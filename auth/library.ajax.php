<?php

require_once 'utils.php';
require_once '../LibraryAJAX.php';


if (!isAuth()) {
    showUsage();
}

$infos = input();
output(login(LibraryAJAX, $infos['username'], $infos['password']));
