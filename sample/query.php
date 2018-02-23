<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

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
        $action = !empty($_GET['action']) ? $_GET['action'] : null;
        if (empty($action)) {
            $action = !empty($_POST['action']) ? $_POST['action'] : null;
        }

        if (empty($merOrderId)) {
            throw new \Exception('商户订单号不能为空');
        }

        if (empty($action)) {
            throw new \Exception('查询类型不能为空');
        }


        $inputObj = new \trhui\data\Query();
//        $inputObj->SetNotifyUrl(NOTIFY_URL);
        $inputObj->SetOriginalMerOrderId($merOrderId);
        if (!empty($orderId)) {
            $inputObj->SetOriginalOrderId($orderId);
        }
        $inputObj->SetAction($action);

        $tpam = new \trhui\extend\Tpam();
        $tpam->serverUrl = SERVER_URL;
        $tpam->merchantNo = MER_CHANT_NO;
        $tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
        $res = $tpam->frontInterface($inputObj, MER_ORDER_ID);
        if (!$res) {
            foreach ($tpam->errors as $error) {
                throw new \Exception($error['errorMsg']);
            }
        }

        if (!$postRes = $tpam->postCurl($res, $tpam->getUrl())) {
            foreach ($tpam->errors as $error) {
                throw new \Exception($error['errorMsg']);
            }
        }

    $resultObj = new \trhui\extend\QueryResults();
    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
    $resultRes = $resultObj->handle($postRes);

    if (!empty($resultObj->errors)) {
        foreach ($resultObj->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }

    $result['error'] = 1;
    $result['msg'] = '提交成功';
    $result['data'] = $resultRes;
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}

var_dump($result);
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>订单查询</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<div>

</div>
</body>
</html>

