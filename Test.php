<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require 'vendor/autoload.php';

defined('ROOT') or define('ROOT', dirname(__FILE__) . '/');
//var_dump(ROOT);
//exit;

//use MiniUpload\MyUpload as MyUpload;
use Trhui\Tpam as Tpam;

$rsaPrivateKeyPath = ROOT . 'rsa/pkcs8_rsa_private_key.pem';
$rsaPublicKeyPath = ROOT . 'rsa/rsa_public_key.pem';
$tpamPublicKeyPath = ROOT . 'rsa/tpampublic.cer';

$tpam = new Tpam();
$tpam->rsaPrivateKeyPath = $rsaPrivateKeyPath;
$tpam->rsaPublicKeyPath = $rsaPublicKeyPath;
$tpam->tpamPublicKeyPath = $tpamPublicKeyPath;
$tpam->test('sssss');