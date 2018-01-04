<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require 'vendor/autoload.php';
use Trhui\Tpam as Tpam;

defined('ROOT') or define('ROOT', dirname(__FILE__) . '/');

$rsaPrivateKeyPath = ROOT . 'rsa/pkcs8_rsa_private_key.pem';
$rsaPublicKeyPath = ROOT . 'rsa/rsa_public_key.pem';
$tpamPublicKeyPath = ROOT . 'rsa/tpampublic.cer';

$inputObj = new \Trhui\data\ToRegister();
$inputObj->SetMerUserId('222');
$inputObj->SetMobile('13000000000');
$inputObj->SetNotifyUrl('https://notify.nongline.cn/trhui');


$tpam = new Tpam('test', $rsaPrivateKeyPath, $rsaPublicKeyPath);
if (!$result = $tpam->toRegister($inputObj, date('YmdHis'))) {
    var_dump($tpam->errors);
    exit;
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>用户注册示例</title>
</head>
<body>
<button onclick='sendData("<?= $tpam->getUrl() ?>",<?= $result ?>)'>提交注册</button>
<script>

    function sendData(action, data) {
        var name,
            form = document.createElement("form"),
            node = document.createElement("input");

        form.action = action;
        form.method = 'post';

        for (name in data) {
            node.name = name;
            node.value = data[name].toString();
            form.appendChild(node.cloneNode());
        }

        // 表单元素需要添加到主文档中.
        form.style.display = "none";
        document.body.appendChild(form);

        form.submit();

        // 表单提交后,就可以删除这个表单,不影响下次的数据发送.
        document.body.removeChild(form);
    }

</script>

</body>
</html>

