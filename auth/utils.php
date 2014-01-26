<?php

function input() {
    return array(
        'username' => $_POST['username'],
        'password' => $_POST['password']
    );
}


function output($infos) {
    header('Content-Type: application/json');
    if (isset($infos['error'])) {
        http_response_code(503);
    }
    echo json_encode($infos);
}


function isAuth() {
    return isset($_POST['username']) && isset($_POST['password']);
}


function showUsage() {
    echo '<p>Post `username` & `password` to <a href=".">me</a>.</p>';
    die();
}


function login($Provider, $username, $password)
{
    try {
        $helper = new $Provider();
        $helper->setup($username, $password);
        $helper->login();
        return $helper->getInfos();
    } catch (Exception $e) {
        return array(
            'error' => $e->getMessage()
        );
    }
}
