<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 18:08
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Read the input from stdin
    $postText = trim(file_get_contents('php://input'));
    $postText = urldecode($postText);
    $postArr = [];
    foreach (explode('&', $postText) as $item) {
        $itemArr = explode('=', $item);
        if(isset($itemArr[0]) && isset($itemArr[1])){
        $postArr[$itemArr[0]] = $itemArr[1];
        }
    }
}
//var_dump($postArr);
//exit;
$result = new \trhui\extend\Results();
$result->tpamPublicKeyPath = PUBLIC_KEY_PATH;
$res = $result->handle($postArr);
var_dump($res);
if (!$res) {
    var_dump($result->errors);
    exit;
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>操作成功页面</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <a href="register.php">注册</a>&nbsp;&nbsp;<a href="pay.php">支付</a>
</div>

</body>
</html>
