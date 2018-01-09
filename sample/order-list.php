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

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>订单列表</title>
    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
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
            <th>操作</th>
        </tr>
        <?php foreach ($orders as $item): ?>
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
                <td><?= isset($item['status']) ? $item['status'] : '' ?></td>
                <td><?= isset($item['request_time']) ? date('Y-m-d H:i:s', $item['request_time']) : '' ?></td>
                <td><?= !empty($item['pay_time']) ? date('Y-m-d H:i:s', substr($item['pay_time'], 0, -3)) : '' ?></td>
                <td><a href="javascript:;" class="delay-auto-payday" value="<?= $item['merOrderId'] ?>">延长自动转账</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<script type="text/javascript">

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

    $('.delay-auto-payday').on('click', function (event) {
        var merOrderId = $(this).attr("value");
        if (merOrderId == undefined || merOrderId == false) {
            alert('数据异常');
        }

        $.ajax({
            type: 'POST',
            url: 'delay-auto-payday.php',
            data: {merOrderId: merOrderId},
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

    $(document).ready(function () {
        $("#submitPay").click(function () {
            $.ajax({
                cache: true,
                type: "POST",
                url: 'pay.php',//提交的URL
                data: $('#payForm').serialize(), // 要提交的表单,必须使用name属性
                async: false,
                dataType: 'json',
                success: function (data) {
                    if (data.error == 0) {
                        alert(data.msg);
                    } else if (data.error == 1) {
//                        console.log(data.data.businessData);
                        sendData(data.data.businessUrl, data.data.businessData);
                    } else {
                        alert('数据异常');
                    }
                },
                error: function (request) {
                    alert("Connection error");
                }
            });
        });
    })
</script>
</body>
</html>

