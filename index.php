<?php
function display_info($info) {
    echo '<p>hi, ' . $info['name'] . ' 同学</p>';
    echo '<p>你的学号是 ' . $info['stu_id'] . '</p>';
    echo '<p>你是' . $info['campus'] . ' ' . $info['faculty'] . ' ' .
        $info['grade'] . '的学生</p>';
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>GDUT Login</title>
    </head>
    <body>
        <form action="" method="POST">
            <input name="username" placeholder="请输入 10 位学号" type="text" />
            <input name="password" placeholder="请输入学生工作信息管理系统密码" type="password" />
            <input type="submit" value="登录" />
        </form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('eswis.php');

    $stu = new ESWISLogin($_POST['username'], $_POST['password']);
    try {
        $stu->login();
        display_info($stu->get_info());
    } catch (CURLException $e) {
        echo '<p>网络错误!</p>';
        die();
    } catch (LoginException $e) {
        echo '<p>' . $e->getMessage() . '</p>';
        die();
    } catch (GetInfoException $e) {
        echo '<p>获取学生信息失败！</p>';
    }
}
?>
    </body>
</html>
