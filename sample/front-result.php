<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 18:08
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

$result = new \trhui\Results();
$result->tpamPublicKeyPath = PUBLIC_KEY_PATH;
$res = $result->handle($_POST);
if (!$res) {
    var_dump($result->errors);
    exit;
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>操作成功页面</title>
</head>
<body>
<div>
    <a href="register.php">注册</a>&nbsp;&nbsp;<a href="pay.php">支付</a>
</div>

<script type="text/javascript" src="jquery-3.2.1.min.js"></script>
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
                url: 'register.php',//提交的URL
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
