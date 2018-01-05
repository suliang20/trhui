<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 18:08
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require '../vendor/autoload.php';

defined('ROOT') or define('ROOT', dirname(dirname(__FILE__)) . '/');

$tpamPublicKeyPath = ROOT . 'rsa/tpamPublic.pem';
//$tpamPublicKeyPath = ROOT . 'rsa/rsa_public_key.pem';

$result = new \trhui\Results();
$result->tpamPublicKeyPath = $tpamPublicKeyPath;
$res = $result->handle($_POST);
if(!$res){
   var_dump($result->errors);
}
//var_dump($res);exit;
exit;
?>