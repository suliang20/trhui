<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./commonParams.php');
require '../vendor/autoload.php';

if (is_post()) {
    $result = [
        'error' => 0,
        'msg' => '提交错误',
    ];
    try {
        do {
            if (!\trhui\business\Register::chkMobile($_POST['mobile'])) {
                throw new \trhui\TpamException('手机号格式错误');
            }
            $mobile = $_POST['mobile'];
            $registerObj = new \trhui\business\Register();
            if ($registerObj->hasMobile($mobile)) {
                throw new \trhui\TpamException('手机号已注册');
            }

            $inputObj = new \trhui\data\ToRegister();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            //  获取商户用户ID
            $merUserId = $registerObj->getNewMerUserId();
            $inputObj->SetMerUserId($merUserId);
            $inputObj->SetMobile($mobile);

            $tpam = new \trhui\Tpam();
            $tpam->serverUrl = SERVER_URL;
            $tpam->merchantNo = MER_CHANT_NO;
            $tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
            $res = $tpam->frontInterface($inputObj, MER_ORDER_ID);
            if (!$res) {
                foreach ($tpam->errors as $error) {
                    throw new \trhui\TpamException($error['errorMsg']);
                }
            }
        } while (false);

        $result['error'] = 1;
        $result['msg'] = '提交成功';
        $result['data']['businessData'] = $res;
        $result['data']['businessUrl'] = $tpam->getUrl();
    } catch (\trhui\TpamException $e) {
        $result['msg'] = $e->getMessage();
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户注册示例</title>
</head>
<body>
<div>
<form action="" method="post" id="payForm" name="payForm">
    <div>
        <label for="mobile">用户手机号</label>
        <input type="text" name="mobile" id="mobile">
    </div>
    <button type="button" id="submitPay">提交注册</button>
</form>
</div>
<div>
    <a href="register-list.php">注册列表</a>
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

