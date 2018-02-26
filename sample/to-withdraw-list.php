<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';


?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>结算订单列表</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
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
            <th>清算通用户ID</th>
            <th>结算金额</th>
            <th>手续费承担方</th>
            <th>业务系统收取会员费用</th>
            <th>手续费</th>
            <th>状态</th>
            <th>请求时间</th>
            <th>返回时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ((new \trhui\business\ToWithdraw())->getAll() as $item): ?>
            <?php
            if (!empty($_GET['userId']) && $item['userId'] != $_GET['userId']) {
                continue;
            }
            if ($item['status'] == 0) {
                $status_name = '结算处理中';
            } elseif ($item['status'] == 3) {
                $status_name = '结算成功';
            } elseif ($item['status'] == 4) {
                $status_name = '结算失败';
            } else {
                $status_name = '未结算';
            }
            ?>
            <tr>
                <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                <td><?= isset($item['platformOrderId']) ? $item['platformOrderId'] : '' ?></td>
                <td><?= isset($item['userId']) ? $item['userId'] : 0 ?></td>
                <td><?= isset($item['amount']) ? $item['amount'] : 0 ?></td>
                <td><?= isset($item['feePayer']) ? $item['feePayer'] : '' ?></td>
                <td><?= isset($item['feeToMerchant']) ? $item['feeToMerchant'] : '' ?></td>
                <td><?= isset($item['fee']) ? $item['fee'] : '' ?></td>
                <!--                    <td>--><? //= isset($item['status']) ? $item['status'] : '' ?><!--</td>-->
                <td><?= $status_name ?></td>
                <td><?= isset($item['requestTime']) ? date('Y-m-d H:i:s', $item['requestTime']) : '' ?></td>
                <td><?= !empty($item['responseTime']) ? date('Y-m-d H:i:s', substr($item['responseTime'], 0, -3)) : '' ?></td>
                <td>
                    <?= $item['status'] == 0 ? '<a href="refund.php?merOrderId=' . $item['merOrderId'] . '">退款</a>' : '' ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

