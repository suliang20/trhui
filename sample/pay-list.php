<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$payRequestOrderObj = new \trhui\business\PayRequestOrder();
$orders = $payRequestOrderObj->getAllOrder();

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>支付列表</title>
    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
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
        </tr>
        <?php foreach ($orders as $item): ?>
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
                <td><?= isset($item['response_status']) ? $item['response_status'] : '' ?></td>
                <td><?= isset($item['remarks']) ? $item['remarks'] : '' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<script type="text/javascript">
    function test() {
        console.log('test');
    }

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

