<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$registerObj= new \trhui\business\Register();
$registers= $registerObj->getAll();
//var_dump($registers);exit;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>注册列表</title>
    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
</head>

<body>
<div>
    <table border="2">
        <tr>
            <th>授权状态</th>
            <th>清算平台用户ID</th>
            <th>商户平台用户ID</th>
            <th>订单号</th>
            <th>手机号</th>
        </tr>
        <?php foreach ($registers as $item): ?>
            <tr>
                <td><?= isset($item['authed']) ? $item['authed'] : '' ?></td>
                <td><?= isset($item['userId']) ? $item['userId'] : '' ?></td>
                <td><?= isset($item['merUserId']) ? $item['merUserId'] : '' ?></td>
                <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
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

