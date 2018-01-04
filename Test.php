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

//echo phpinfo();exit;

//use MiniUpload\MyUpload as MyUpload;
use Trhui\Tpam as Tpam;

$rsaPrivateKeyPath = ROOT . 'rsa/pkcs8_rsa_private_key.pem';
$rsaPublicKeyPath = ROOT . 'rsa/rsa_public_key.pem';
$tpamPublicKeyPath = ROOT . 'rsa/tpampublic.cer';

$inputObj = new \Trhui\data\ToRegister();
$inputObj->SetMerUserId('222');
$inputObj->SetMobile('13959260751');
$inputObj->SetNotifyUrl('https://notify.nongline.cn/trhui');


$tpam = new Tpam('ssss', $rsaPrivateKeyPath, $rsaPublicKeyPath);
if (!$result = $tpam->toRegister($inputObj, date('YmdHis'))) {
    var_dump($tpam->errors);
    exit;
}
echo $result;

