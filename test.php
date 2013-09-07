<?php
require_once('jwgl.php');

$stu = new JWGLogin();
?>
<!doctype html>
<html>
    <body>
       <form action="" method="POST">
           <input type="hidden" name="vs" value="<?php $stu->get_viewstate(); ?>"/>
           <input type="text" name="username" />
           <input type="password" name="password" />
           <input type="text" name="code" />
           <input type="submit" />
       </form> 
    </body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ret = $stu->_login($_POST['username'], $_POST['password'], $_POST['code'],
                        $_POST['vs']);
    var_dump($ret);
} else {
    echo $stu->get_code();
}
?>
