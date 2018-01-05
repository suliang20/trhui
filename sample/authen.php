<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require '../vendor/autoload.php';

defined('ROOT') or define('ROOT', dirname(dirname(__FILE__)) . '/');

$rsaPrivateKeyPath = ROOT . 'rsa/pkcs8_rsa_private_key.pem';

$inputObj = new \trhui\data\ToAuthen();
$inputObj->SetUserId('510');
$inputObj->SetNotifyUrl('http://notify.nongline.cn/trhui');
$inputObj->SetFrontUrl('http://git-dev.com/composer/trhui/sample/front-result.php');

$tpam = new \trhui\Tpam();
$tpam->merchantNo = 'test';
$tpam->rsaPrivateKeyPath = $rsaPrivateKeyPath;
$result = $tpam->frontInterface($inputObj, date('YmdHis'));
if (!$result) {
    var_dump($tpam->errors);
    exit;
}
//var_dump($result);exit;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户实名认证</title>
</head>
<body>
<button onclick='sendData("<?= $tpam->getUrl() ?>",<?= $result ?>)'>提交</button>
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

