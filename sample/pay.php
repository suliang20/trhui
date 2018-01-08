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
            $inputObj = new \trhui\data\OrderTransfer();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
                throw new \trhui\TpamException('支付金额错误');
            }
            $amount = $_POST['amount'];
            $inputObj->SetAmount($amount * 100);
            $inputObj->SetPayerUserId(0);
            $inputObj->SetActionType(\trhui\data\OrderTransfer::ACTION_TYPE_CONSUME);
            $inputObj->SetTransferPayType($_POST['transfer_pay_type']);
            $inputObj->SetTopupType($_POST['topup_type']);
            $inputObj->SetPayType(0);
            $inputObj->SetFeePayer();

            $paramArr1 = [
                'orderId' => ORDER_ID,
                'payeeUserId' => 526,
                'payeeAmount' => $amount * 100,
                'feeToMerchant' => 0,
                'transferType' => $_POST['transfer_type'],
                'feeType' => 1
            ];

            $payeeUserListArrObj = new \trhui\data\ParamsArray();
            if (!$payeeUserListArrObj->SetParams(new \trhui\data\PayeeUserList(), $paramArr1)) {
                foreach ($payeeUserListArrObj->errors as $error) {
                    throw new \trhui\TpamException($error['errorMsg']);
                }
            }
            $inputObj->SetPayeeUserList($payeeUserListArrObj->getParamsArr());

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
    <title>支付</title>
    <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
</head>

<body>
<form action="pay.php" method="post" id="payForm" name="payForm">
    <div>
        <label for="transferPayType">支付方式</label>
        <select name="transfer_pay_type" id="transferPayType">
            <?php foreach (\trhui\data\OrderTransfer::TRANSFER_PAY_TYPE as $key => $name): ?>
                <option value="<?= $key ?>"<?= $key == \trhui\data\OrderTransfer::TRANSFER_PAY_TYPE_ONLINE ? 'selected="selected"' : '' ?>><?= $name ?></option>>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="topupType">支付类型</label>
        <select name="topup_type" id="topupType">
            <?php foreach (\trhui\data\OrderTransfer::TOPUP_TYPE as $key => $name): ?>
                <option value="<?= $key ?>"<?= $key == \trhui\data\OrderTransfer::TOPUP_TYPE_WECHAT_SCAN ? 'selected="selected"' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="topupType">转账方式</label>
        <select name="transfer_type" id="transfer_type">
            <?php foreach (\trhui\data\PayeeUserList::TRANSFER_TYPE as $key => $name): ?>
                <option value="<?= $key ?>" <?= $key == \trhui\data\PayeeUserList::TRANSFER_TYPE_COSTODY ? 'selected="selected"' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="amount">支付金额</label>
        <input type="text" name="amount" id="amount" value="0.01">
    </div>
    <div>
        <label for="payeeUserId">收款用户</label>
        <select name="payee_user_id" id="payeeUserId">
            <?php foreach ((new \trhui\business\Register())->getAllRegister() as $key => $value): ?>
                <option value="<?= $value['userId'] ?>"><?= $value['mobile'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="button" id="submitPay">提交支付</button>
</form>
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

