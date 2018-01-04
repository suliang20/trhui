<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require 'vendor/autoload.php';

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

defined('ROOT') or define('ROOT', dirname(__FILE__) . '/');
//var_dump(ROOT);
//exit;

//use MiniUpload\MyUpload as MyUpload;
use Trhui\Tpam as Tpam;

$rsaPrivateKeyPath = ROOT . 'rsa/pkcs8_rsa_private_key.pem';
$rsaPublicKeyPath = ROOT . 'rsa/rsa_public_key.pem';
$tpamPublicKeyPath = ROOT . 'rsa/tpampublic.cer';

$inputObj = new \Trhui\data\ToRegister();
$inputObj->SetMerUserId('222');


$tpam = new Tpam('ssss', $rsaPrivateKeyPath, $rsaPublicKeyPath);
$tpam->toRegister($inputObj, time());
var_dump($tpam->errors);
exit;
