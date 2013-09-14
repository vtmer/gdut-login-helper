<?php 
require_once('library.ajax.php');

$lib = new LibraryAjaxLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>library login</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="学号"/>
        <input type="password" name="password" placeholder="图书馆密码"/>
        <button>登录</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ret = $lib->login($_POST['username'], $_POST['password']);
        var_dump($ret);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
