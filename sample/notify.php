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
    exit;
}


