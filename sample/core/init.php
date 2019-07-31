<?php /**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/5
 * Time: 19:13
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('PRC');//其中PRC为“中华人民共和国”

//  项目根目录
defined('ROOT') or define('ROOT', realpath(dirname(dirname(dirname(__FILE__)))) . '/');
//  引入函数文件
require_once(ROOT . 'sample/core/function.php');

//  协议头
defined('PROTOCOL') or define('PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http');
//  域名
defined('DOMAIN') or define('DOMAIN', $_SERVER['HTTP_HOST']);
//  端口号
defined('PORT') or define('PORT', $_SERVER['SERVER_PORT']);
//  主机名
if (PORT == 80 || PORT == 443) {
    defined('HOST') or define('HOST', PROTOCOL . '://' . DOMAIN . '/');
} else {
    defined('HOST') or define('HOST', PROTOCOL . '://' . DOMAIN . ':' . PORT . '/');
}
//  URL
defined('URL') or define('URL', GetCurrentUrl());


//  默认配置地址
defined('CONFIG_FILE') or define('CONFIG_FILE', ROOT . 'sample/config/config.php');
//  本地配置地址
defined('CONFIG_LOCAL_FILE') or define('CONFIG_LOCAL_FILE', ROOT . 'sample/config/config-local.php');


//  TODO    合并配置
if (!file_exists(CONFIG_LOCAL_FILE)) {
    die("本地配置文件 " . CONFIG_LOCAL_FILE . " 不存在");
}
$config = merge(
    require_once(CONFIG_FILE),
    require_once(CONFIG_LOCAL_FILE)
);


//  TODO    证书相关
//  商户平台私钥路径
defined('PRIVATE_KEY_PATH') OR define('PRIVATE_KEY_PATH', ROOT . $config['private_key']);
//  招行清算通公钥路径
defined('PUBLIC_KEY_PATH') OR define('PUBLIC_KEY_PATH', ROOT . $config['public_key']);

//  TODO    清算通相关
//  商户号
defined('MER_CHANT_NO') or define('MER_CHANT_NO', $config['mer_chant_no']);
//  招商清算通服务地址
defined('SERVER_URL') or define('SERVER_URL', $config['server_url']);


//  回调地址
defined('NOTIFY_URL') or define('NOTIFY_URL', URL . 'back_result.php');
//  前台地址
defined('FRONT_URL') or define('FRONT_URL', URL . 'front-result.php');


//  商户平台订单号
defined('MER_ORDER_ID') or define('MER_ORDER_ID', $config['mer_order_id']);
//  商户平台交易订单号
defined('ORDER_ID') or define('ORDER_ID', $config['order_id']);




