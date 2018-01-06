<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$inputObj = new \trhui\data\Accredit();
$inputObj->SetNotifyUrl(NOTIFY_URL);
$inputObj->SetFrontUrl(FRONT_URL);

$inputObj->SetUserId(USER_ID);

$tpam = new \trhui\Tpam();
$tpam->serverUrl = SERVER_URL;
$tpam->merchantNo = MER_CHANT_NO;
$tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
$result = $tpam->frontInterface($inputObj, MER_ORDER_ID);
if (!$result) {
    var_dump($tpam->errors);
    exit;
}
//var_dump($result);exit;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户授权</title>
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

