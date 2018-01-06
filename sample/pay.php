<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$inputObj = new \trhui\data\OrderTransfer();
$inputObj->SetNotifyUrl(NOTIFY_URL);
$inputObj->SetFrontUrl(FRONT_URL);

$inputObj->SetAmount(100);
$inputObj->SetPayerUserId(USER_ID);
$inputObj->SetActionType(1);
$inputObj->SetTransferPayType(0);
$inputObj->SetTopupType(1);
$inputObj->SetPayType();
$inputObj->SetFeePayer();

$paramArr1 = [
    'orderId' => ORDER_ID,
    'payeeUserId' => MER_USER_ID,
    'payeeAmount' => 1,
    'feeToMerchant' => 0,
    'transferType' => 1,
    'feeType' => 1
];

$payeeUserListArrObj = new \trhui\data\ParamsArray();
if (!$payeeUserListArrObj->SetParams(new \trhui\data\PayeeUserList(), $paramArr1)) {
    var_dump($payeeUserListArrObj->errors);
    exit;
}
$inputObj->SetPayeeUserList($payeeUserListArrObj->getParamsArr());

$tpam = new \trhui\Tpam();
$tpam->serverUrl = SERVER_URL;
$tpam->merchantNo = MER_CHANT_NO;
$tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
$result = $tpam->frontInterface($inputObj, MER_ORDER_ID);
if (!$result) {
    var_dump($tpam->errors);
    exit;
}
//var_dump($result);
//exit;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>支付</title>
</head>
<body>
<button onclick='sendData("<?= $tpam->getUrl() ?>",<?= $result ?>)'>提交支付</button>
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

