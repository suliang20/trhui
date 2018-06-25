<?php /**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/5
 * Time: 19:13
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('PRC');//其中PRC为“中华人民共和国”

defined('PROTOCOL') or define('PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http');

defined('DOMAIN') or define('DOMAIN', $_SERVER['HTTP_HOST']);
defined('PORT') or define('PORT', $_SERVER['SERVER_PORT']);

if (PORT == 80 || PORT == 443) {
    defined('HOST') or define('HOST', PROTOCOL . '://' . DOMAIN . '/');
} else {
    defined('HOST') or define('HOST', PROTOCOL . '://' . DOMAIN . ':' . PORT . '/');
}

//  项目根目录
defined('ROOT') or define('ROOT', realpath(dirname(dirname(__FILE__))) . '/');
//  本地配置文件路径
defined('CONFIG_LOCAL') or define('CONFIG_LOCAL', ROOT . 'rsa/config-local.php');

//  本地配置文件存在加载配置文件
if (file_exists(CONFIG_LOCAL)) {
    require_once(CONFIG_LOCAL);
} else {
    //  TODO    证书相关
    //  商户平台私钥路径
    defined('PRIVATE_KEY_PATH') OR define('PRIVATE_KEY_PATH', ROOT . 'rsa/pkcs8_rsa_private_key.pem');
    //  招行清算通公钥路径
    defined('PUBLIC_KEY_PATH') OR define('PUBLIC_KEY_PATH', ROOT . 'rsa/tpamPublic.pem');

    //  TODO    清算通相关
    //  商户号
    defined('MER_CHANT_NO') or define('MER_CHANT_NO', 'test');
    //  招商清算通服务地址
    defined('SERVER_URL') or define('SERVER_URL', 'http://cmbtest.trhui.com/');


    //  回调地址
    defined('NOTIFY_URL') or define('NOTIFY_URL', 'http://test.com/back-result.php');
    //  前台地址
    defined('FRONT_URL') or define('FRONT_URL', 'http://test.com/front-result.php');


    //  商户平台订单号
    defined('MER_ORDER_ID') or define('MER_ORDER_ID', date('YmdHis'));
    //  商户平台交易订单号
    defined('ORDER_ID') or define('ORDER_ID', date('YmdHis'));
}

//  判断是否post提交
function is_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
}

//  判断是否post提交
function is_get()
{
    return $_SERVER['REQUEST_METHOD'] == 'get' ? true : false;
}

