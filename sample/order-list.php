<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

$payOrderObj = new \trhui\business\PayOrder();
$orders = $payOrderObj->getAllOrder();

$merOrderId = !empty($_GET['merOrderId']) ? $_GET['merOrderId'] : null;
if (!empty($merOrderId)) {
    $orders = !empty($orders[$merOrderId]) ? [$orders[$merOrderId]] : [];
}

?>

<html>
<head>
    <title>订单列表</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>

<body>
<?php
require_once "common-link.php";
?>
<div>
    <table>
        <tr>
            <th>商户订单号</th>
            <th>响应单号</th>
            <th>交易订单号</th>
            <th>收款方ID</th>
            <th>付款方用户ID</th>
            <th>收款金额</th>
            <th>佣金</th>
            <th>转帐方式</th>
            <th>自动支付(天)</th>
            <th>佣金方式</th>
            <th>状态</th>
            <th>请求时间</th>
            <th>支付时间</th>
            <th>自动审核时间</th>
            <th>延长自动转账</th>
            <th>操作</th>
            <th>查询</th>
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
                    <td><?= !empty($item['autoAuditDate']) ? date('Y-m-d H:i:s', substr($item['autoAuditDate'], 0, -3)) : '' ?></td>
                    <td>
                        <?php if ($item['status'] == 0): ?>
                            <a href="javascript:;" class="delay-auto-payday"
                               value="<?= $item['merOrderId'] ?>" orderid= <?= $item['orderId'] ?>>延长自动转账</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $item['status'] == 0 ? '<a href="refund.php?merOrderId=' . $item['merOrderId'] . '&orderId=' . $item['orderId'] . '">退款</a>' : '' ?>
                        <?= $item['status'] == 0 ? '<a href="order-transfer-audit.php?merOrderId=' . $item['merOrderId'] . '&orderId=' . $item['orderId'] . '">审核</a>' : '' ?>
                    </td>
                    <td>
                        <a href="query.php?merOrderId=<?= $item['merOrderId'] ?>&orderId=<?= $item['orderId'] ?>&action=<?= \trhui\data\Query::ACTION_PAYMENT ?>">支付查询</a>
                        <a href="query.php?merOrderId=<?= $item['merOrderId'] ?>&orderId=<?= $item['orderId'] ?>&action=<?= \trhui\data\Query::ACTION_TRANSFER_AUDIT?>">支付审核查询</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
</div>

<script type="text/javascript">
    $('.delay-auto-payday').on('click', function (event) {
        var merOrderId = $(this).attr("value");
        if (merOrderId == undefined || merOrderId == false) {
            alert('数据异常');
        }
        var orderId = $(this).attr("orderid");
        if (orderId == undefined || orderId == false) {
            alert('数据异常');
        }

        $.ajax({
            type: 'POST',
            url: 'delay-auto-payday.php',
            data: {merOrderId: merOrderId, orderId: orderId},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.error == 0) {
                    alert(data.msg);
                } else if (data.error == 1) {
                    alert(data.msg);
//                    sendData(data.data.businessUrl, data.data.businessData);
                } else {
                    alert('数据异常');
                }
            },
            error: function (request) {
                alert("提交错误");
            }
        })
    })
</script>
</body>
</html>

