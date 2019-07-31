<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

$result = [
    'error' => 0,
    'msg' => '提交错误',
];
try {
    $merOrderId = !empty($_GET['merOrderId']) ? $_GET['merOrderId'] : null;
    if (empty($merOrderId)) {
        $merOrderId = !empty($_POST['merOrderId']) ? $_POST['merOrderId'] : null;
    }
    $orderId = !empty($_GET['orderId']) ? $_GET['orderId'] : null;
    if (empty($orderId)) {
        $orderId = !empty($_POST['orderId']) ? $_POST['orderId'] : null;
    }
    $auditType = !empty($_GET['auditType']) ? $_GET['auditType'] : null;
    if (empty($auditType)) {
        $auditType = !empty($_POST['auditType']) ? $_POST['auditType'] : \trhui\data\OrderTransferAudit::AUDIT_TYPE_PASS;
    }

    if (empty($merOrderId)) {
        throw new \Exception('商户订单号不能为空');
    }
    if (empty($orderId)) {
        throw new \Exception('交易订单号不能为空');
    }

    $payOrderObj = new \trhui\business\PayOrder();
    $payOrder = $payOrderObj->getPayOrder($merOrderId, $orderId);
    if (!$payOrder) {
        throw new \Exception('交易订单不存在');
    }

    $inputObj = new \trhui\data\OrderTransferAudit();
    $inputObj->SetNotifyUrl(NOTIFY_URL);
    $inputObj->SetFrontUrl(FRONT_URL);

    $inputObj->SetOriginalPlatformOrder($payOrder['platformOrderId']);
    $inputObj->SetOriginalOrderId($payOrder['orderId']);
//    $inputObj->SetAmount($payOrder['payeeAmount']);
    $inputObj->SetAuditType($auditType);

    $tpam = new \trhui\extend\Tpam();
    $tpam->serverUrl = SERVER_URL;
    $tpam->merchantNo = MER_CHANT_NO;
    $tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
    $res = $tpam->frontInterface($inputObj, MER_ORDER_ID);

//    var_dump($res);
//    exit;

    if (!$res) {
        foreach ($tpam->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }

//    if (!$postRes = $tpam->postCurl($res, $tpam->getUrl(), false, 120)) {
//        foreach ($tpam->errors as $error) {
//            throw new \Exception($error['errorMsg']);
//        }
//    }
//
//    var_dump($tpam->errors);
//    var_dump($postRes);exit;
//
//    $resultObj = new \trhui\extend\Results();
//    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
//    $resultRes = $resultObj->handle($postRes);
//    if (!empty($resultObj->errors)) {
//        foreach ($resultObj->errors as $error) {
//            throw new \Exception($error['errorMsg']);
//        }
//    }

//    $result['error'] = 1;
//    $result['msg'] = '提交成功';
//    $result['data'] = $resultRes;
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}

//echo json_encode($result, JSON_UNESCAPED_UNICODE);
//exit;

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>支付审核</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<script type="text/javascript">
    var url = '<?= $tpam->getUrl() ?>';
    var data = <?=json_encode($res, JSON_UNESCAPED_UNICODE)?>;
    sendData(url, data);
</script>
</body>
</html>

