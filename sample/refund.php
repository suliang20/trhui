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
    if (true) {
        $merOrderId = !empty($_GET['merOrderId']) ? $_GET['merOrderId'] : null;
        if (empty($merOrderId)) {
            $merOrderId = !empty($_POST['merOrderId']) ? $_POST['merOrderId'] : null;
        }
        $orderId = !empty($_GET['orderId']) ? $_GET['orderId'] : null;
        if (empty($orderId)) {
            $orderId = !empty($_POST['orderId']) ? $_POST['orderId'] : null;
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

        $inputObj = new \trhui\data\Refund();
        $inputObj->SetNotifyUrl(NOTIFY_URL);
        $inputObj->SetFrontUrl(FRONT_URL);

        $inputObj->SetOriginalPlatformOrderId($payOrder['platformOrderId']);
        $inputObj->SetOriginalMerOrderId($payOrder['merOrderId']);
        $inputObj->SetOriginalOrderId($payOrder['orderId']);
        $inputObj->SetUserId($payOrder['payerUserId']);
        $inputObj->SetAmount($payOrder['payeeAmount']);
        $inputObj->SetRefundType(\trhui\data\Refund::REFUND_TYPE_TRANSACTION);

        $tpam = new \trhui\extend\TpamExtend();
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
    }

//    $postRes = <<<JSON
//{"resultObj":{"merOrderId":"20180208165607","platformOrderId":"TK20180208002289","userId":"167","refundType":"1","amount":1,"status":"0"},"result":"{\"amount\":1,\"status\":\"0\",\"userId\":\"167\",\"merOrderId\":\"20180208165607\",\"platformOrderId\":\"TK20180208002289\",\"parameter1\":null,\"refundType\":\"1\"}","sign":"G7JPjZcNaxY8jhhdbJWeurn+Got5MmTXCZ9BeUM1O\/u7944yS4YNaYqb0tZG8EJW3rhJ8XGSWbBTy7+i\/QdGcNj0FMqDy0F\/5HHm5UcrehFI1Ib4X4JU7vZ6GA4\/\/zwV9qc0KvJBbuZ2PBq6P1QsFhLC\/vIjbu2tXlW0EjPS5cY=","code":"100","msg":"操作成功","date":"1518080167975","version":"1.0"}
//JSON;
//    $postRes = json_decode($postRes, true);
//    var_dump($postRes);exit;

    $resultObj = new \trhui\Results();
    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
    $resultRes = $resultObj->handle($postRes);

    if (!empty($resultObj->errors)) {
        foreach ($resultObj->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }

//    echo (json_encode($postRes, JSON_UNESCAPED_UNICODE));
//    echo PHP_EOL;

    $result['error'] = 1;
    $result['msg'] = '提交成功';
    $result['data'] = $resultRes;
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit;

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>支付列表</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<div>
    <table border="2">
        <tr>
            <th>商户订单号</th>
            <th>清算通系统订响应单号</th>
            <th>请求金额</th>
            <th>返回金额</th>
            <th>付款用户ID</th>
            <th>支付方式</th>
            <th>支付类型</th>
            <th>支付手续费承担方</th>
            <th>状态</th>
            <th>支付状态</th>
            <th>摘要备注</th>
            <th>请求时间</th>
            <th>支付时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($orders as $item): ?>
            <?php
            if ($item['response_status'] == 0) {
                $status_name = '已支付';
            } elseif ($item['response_status'] == 1) {
                $status_name = '支付失败';
            } elseif ($item['response_status'] == 2) {
                $status_name = '支付待确认';
            } else {
                $status_name = '未支付';
            }
            ?>
            <tr>
                <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                <td><?= isset($item['platformOrderId']) ? $item['platformOrderId'] : '' ?></td>
                <td><?= isset($item['request_amount']) ? $item['request_amount'] : '' ?></td>
                <td><?= isset($item['response_amount']) ? $item['response_amount'] : '' ?></td>
                <td><?= isset($item['payerUserId']) ? $item['payerUserId'] : '' ?></td>
                <td><?= isset($item['transferPayType']) ? $item['transferPayType'] : '' ?></td>
                <td><?= isset($item['topupType']) ? $item['topupType'] : '' ?></td>
                <td><?= isset($item['feePayer']) ? $item['feePayer'] : '' ?></td>
                <td><?= isset($item['status']) ? $item['status'] : '' ?></td>
                <td><?= $status_name ?></td>
                <td><?= isset($item['remarks']) ? $item['remarks'] : '' ?></td>
                <td><?= isset($item['request_time']) ? date('Y-m-d H:i:s', $item['request_time']) : '' ?></td>
                <td><?= !empty($item['pay_time']) ? date('Y-m-d H:i:s', substr($item['pay_time'], 0, -3)) : '' ?></td>
                <td><?= $item['response_status'] == 0 ? '<a href="refund.php?merOrderId=' . $item['merOrderId'] . '">退款</a>' : '' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

