<html>
<head>
<meta charset="utf-8" />
<title>Library Login</title>
</head>
<body>
<form action="" method="post">
<input name="username" placeholder="请输入 10 位学号" type="text" />
<input name="password" placeholder="请输入密码" type="password" />
<input type="submit" value="登录" />
</form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('library.php');
    $stu = new LibrayLogin($_POST['username'], $_POST['password']);
    try {
    	$stu->login();
    }catch(LoginException $e) {
        echo '<p>' . $e->getMessage() . '</p>';
        die();
    }
}
?>