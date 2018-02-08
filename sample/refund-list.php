<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

$payOrderObj = new \trhui\business\Refund();
$orders = $payOrderObj->getAll();

var_dump($orders);exit;
$merOrderId = !empty($_GET['merOrderId']) ? $_GET['merOrderId'] : null;
if (!empty($merOrderId)) {
    $orders = !empty($orders[$merOrderId]) ? [$orders[$merOrderId]] : [];
}

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>退款订单列表</title>
    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="trhui.js"></script>
    <style>
        table {
            border-collapse: collapse;
            font-size: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
<div>
    <table>
        <tr>
            <th>商户订单号</th>
            <th>清算通系统订响应单号</th>
            <th>交易订单号</th>
            <th>收款方清算通用户ID</th>
            <th>付款方清算通用户ID</th>
            <th>收款金额</th>
            <th>商户平台收取佣金</th>
            <th>转帐方式</th>
            <th>自动支付时间(天)</th>
            <th>佣金收取方式</th>
            <th>状态</th>
            <th>请求时间</th>
            <th>支付时间</th>
            <th>延长自动转账</th>
            <th>操作</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <?php foreach ($order as $item): ?>
                <?php
                if ($item['status'] == 0) {
                    $status_name = '已支付';
                } elseif ($item['status'] == 1) {
                    $status_name = '支付失败';
                } elseif ($item['status'] == 2) {
                    $status_name = '支付待确认';
                } else {
                    $status_name = '未支付';
                }
                ?>
                <tr>
                    <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                    <td><?= isset($item['platformOrderId']) ? $item['platformOrderId'] : '' ?></td>
                    <td><?= isset($item['orderId']) ? $item['orderId'] : '' ?></td>
                    <td><?= isset($item['payeeUserId']) ? $item['payeeUserId'] : '' ?></td>
                    <td><?= isset($item['payerUserId']) ? $item['payerUserId'] : '' ?></td>
                    <td><?= isset($item['payeeAmount']) ? $item['payeeAmount'] : '' ?></td>
                    <td><?= isset($item['feeToMerchant']) ? $item['feeToMerchant'] : '' ?></td>
                    <td><?= isset($item['transferType']) ? $item['transferType'] : '' ?></td>
                    <td><?= isset($item['autoPayday']) ? $item['autoPayday'] : 0 ?></td>
                    <td><?= isset($item['feeType']) ? $item['feeType'] : '' ?></td>
                    <!--                    <td>--><? //= isset($item['status']) ? $item['status'] : '' ?><!--</td>-->
                    <td><?= $status_name ?></td>
                    <td><?= isset($item['request_time']) ? date('Y-m-d H:i:s', $item['request_time']) : '' ?></td>
                    <td><?= !empty($item['pay_time']) ? date('Y-m-d H:i:s', substr($item['pay_time'], 0, -3)) : '' ?></td>
                    <td>
                        <a href="javascript:;" class="delay-auto-payday" value="<?= $item['merOrderId'] ?>">延长自动转账</a>
                    </td>
                    <td>
                        <?= $item['status'] == 0 ? '<a href="refund.php?merOrderId=' . $item['merOrderId'] . '&orderId=' . $item['orderId'] . '">退款</a>' : '' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

