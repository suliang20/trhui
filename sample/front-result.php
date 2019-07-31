<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 18:08
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

$result = new \trhui\extend\Results();
$result->tpamPublicKeyPath = PUBLIC_KEY_PATH;
$res = $result->handle($_POST);
var_dump($res);
if (!$res) {
    var_dump($result->errors);
}
var_dump($result->merOrderId);
?>

<html>
<head>
    <title>操作成功页面</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>
<body>
<?php
require_once "common-link.php";
?>

</body>
</html>
