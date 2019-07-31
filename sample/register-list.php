<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

$registerObj = new \trhui\business\Register();
$registers = $registerObj->getAll();
//var_dump($registers);exit;

?>

<html>
<head>
    <title>注册列表</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>

<body>
<?php
require_once "common-link.php";
?>

<?php require_once 'user-list.php' ?>

</body>
</html>

