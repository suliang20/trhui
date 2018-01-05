<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 18:08
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$result = new \trhui\Results();
$result->tpamPublicKeyPath = PUBLIC_KEY_PATH;
$res = $result->handle($_POST);
if(!$res){
   var_dump($result->errors);
}
//var_dump($res);exit;
exit;
?>