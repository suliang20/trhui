<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

$payRequestOrderObj = new \trhui\business\PayRequestOrder();
$orders = $payRequestOrderObj->getAll();

?>

<html>
<head>
    <title>支付列表</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>

<body>
<?php
require_once "common-link.php";
?>
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
                <td><a href="order-list.php?merOrderId=<?= $item['merOrderId'] ?>"><?= $item['merOrderId'] ?></a>
                </td>
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
                <td>
                    <a href="query.php?merOrderId=<?= $item['merOrderId'] ?>&action=<?= \trhui\data\Query::ACTION_PAYMENT ?>">支付查询</a>
                    <a href="query.php?merOrderId=<?= $item['merOrderId'] ?>&action=<?= \trhui\data\Query::ACTION_TRANSFER_AUDIT ?>">支付到账查询</a>
                    <?php if ($item['response_status'] == 0): ?>
                        <a href="refund-all.php?merOrderId=<?= $item['merOrderId'] ?>">全额退款</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

