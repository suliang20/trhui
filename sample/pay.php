<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

if (is_post()) {
    $result = [
        'error' => 0,
        'msg' => '提交错误',
    ];
    try {
        do {
            if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
                throw new \trhui\TpamException('支付金额错误');
            }
            if (!isset($_POST['payee_user_id']) || !is_numeric($_POST['payee_user_id'])) {
                throw new \trhui\TpamException('收款用户ID不能为空');
            }
            if (!isset($_POST['payer_user_id']) || !is_numeric($_POST['payer_user_id'])) {
                throw new \trhui\TpamException('付款用户ID不能为空');
            }

            $inputObj = new \trhui\data\OrderTransfer();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            $amount = $_POST['amount'];
            $inputObj->SetAmount($amount * 100);
            $inputObj->SetPayerUserId($_POST['payer_user_id']);
            $inputObj->SetActionType(\trhui\data\OrderTransfer::ACTION_TYPE_CONSUME);
            $inputObj->SetTransferPayType($_POST['transfer_pay_type']);
            $inputObj->SetTopupType($_POST['topup_type']);
            $inputObj->SetPayType(0);
            $inputObj->SetFeePayer();

            $paramArr1 = [
                'orderId' => ORDER_ID,
                'payeeUserId' => $_POST['payee_user_id'],
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

            $tpam = new \trhui\extend\Tpam();
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

        $option = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => http_build_query($res),
            ]
        ];

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
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<div>
    <div>
        <form action="" method="post" id="trhuiForm" name="trhuiForm">
            <div>
                <div>
                    <label for="payeeUserId">付款用户</label>
                    <select name="payer_user_id" id="payerUserId">
                        <option value="0">商户平台</option>
                        <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                            <?php if (isset($value['userId'])): ?>
                                <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                    <option value="0">商户平台</option>
                    <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                        <?php if (isset($value['userId'])): ?>
                            <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" id="trhuiSubmit">提交支付</button>
            <a href="register.php">注册用户</a>
        </form>
    </div>
    <div>
        <a href="pay-list.php">支付列表</a>&nbsp;<a href="order-list.php">订单列表</a>
    </div>
</body>
</html>

