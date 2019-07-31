<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

if (is_post()) {
    $result = [
        'error' => 0,
        'msg' => '提交错误',
    ];
    try {
        do {
            if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
                throw new \Exception('支付金额错误');
            }
            $totalAmount = $_POST['amount'] * 100;
            if (!isset($_POST['payeeUserId']) || !is_numeric($_POST['payeeUserId'])) {
                throw new \Exception('收款用户ID不能为空');
            }
            if (!isset($_POST['payerUserId']) || !is_numeric($_POST['payerUserId'])) {
                throw new \Exception('付款用户ID不能为空');
            }
            $feeToMerchant = !empty($_POST['feeToMerchant']) ? $_POST['feeToMerchant'] * 100 : 0;

            $amount = $totalAmount - $feeToMerchant;

            $inputObj = new \trhui\data\OrderTransfer();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            $inputObj->SetAmount($totalAmount);
            $inputObj->SetPayerUserId($_POST['payerUserId']);
            $inputObj->SetActionType(\trhui\data\OrderTransfer::ACTION_TYPE_CONSUME);
            $inputObj->SetTransferPayType($_POST['transferPayType']);
            $inputObj->SetTopupType($_POST['topupType']);
            $inputObj->SetPayType($_POST['payType']);
            $inputObj->SetFeePayer(\trhui\data\OrderTransfer::FEE_PAYER_PAYEE);

            $paramArr1 = [
                'orderId' => ORDER_ID,
                'payeeUserId' => $_POST['payeeUserId'],
                'payeeAmount' => $amount,
                'feeToMerchant' => $feeToMerchant,
                'transferType' => $_POST['transferType'],
                'feeType' => $_POST['feeType']
            ];

            $payeeUserListArrObj = new \trhui\data\ParamsArray();
            if (!$payeeUserListArrObj->SetParams(new \trhui\data\PayeeUserList(), $paramArr1)) {
                foreach ($payeeUserListArrObj->errors as $error) {
                    throw new \Exception($error['errorMsg']);
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
                    throw new \Exception($error['errorMsg']);
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
    } catch (\Exception $e) {
        $result['msg'] = $e->getMessage();
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
?>

<html>
<head>
    <title>支付</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>

<body>
<?php
require_once "common-link.php";
?>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <div>
                <label for="payerUserId">付款用户</label>
                <select name="payerUserId" id="payerUserId">
                    <option value="0">商户平台</option>
                    <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                        <?php if (isset($value['userId'])): ?>
                            <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="transferPayType">支付方式</label>
            <select name="transferPayType" id="transferPayType">
                <?php foreach (\trhui\data\OrderTransfer::$TRANSFER_PAY_TYPE as $key => $name): ?>
                    <option value="<?= $key ?>"<?= $key == \trhui\data\OrderTransfer::TRANSFER_PAY_TYPE_ONLINE ? 'selected="selected"' : '' ?>><?= $name ?></option>>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="topupType">支付类型</label>
            <select name="topupType" id="topupType">
                <?php foreach (\trhui\data\OrderTransfer::$TOPUP_TYPE as $key => $name): ?>
                    <option value="<?= $key ?>"<?= $key == \trhui\data\OrderTransfer::TOPUP_TYPE_WECHAT_SCAN ? 'selected="selected"' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="payType">卡种</label>
            <select name="payType" id="payType">
                <?php foreach (\trhui\data\OrderTransfer::$PAY_TYPE as $key => $name): ?>
                    <option value="<?= $key ?>"<?= $key == \trhui\data\OrderTransfer::TOPUP_TYPE_WECHAT_SCAN ? 'selected="selected"' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="transferType">转账方式</label>
            <select name="transferType" id="transferType">
                <?php foreach (\trhui\data\PayeeUserList::$TRANSFER_TYPE as $key => $name): ?>
                    <option value="<?= $key ?>" <?= $key == \trhui\data\PayeeUserList::TRANSFER_TYPE_COSTODY ? 'selected="selected"' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="feeType">佣金收取方式</label>
            <select name="feeType" id="feeType">
                <?php foreach (\trhui\data\PayeeUserList::$FEE_TYPE as $key => $name): ?>
                    <option value="<?= $key ?>" <?= $key == \trhui\data\PayeeUserList::FEE_TYPE_PROMPTLY ? 'selected="selected"' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="amount">支付金额</label>
            <input type="text" name="amount" id="amount" value="0.01">
        </div>
        <div>
            <label for="feeToMerchant">佣金</label>
            <input type="text" name="feeToMerchant" id="feeToMerchant" value="0.00">
        </div>
        <div>
            <label for="payeeUserId">收款用户</label>
            <input type="text" name="payeeUserId" id="payeeUserId">
        </div>
        <button type="button" id="trhuiSubmit">提交支付</button>
    </form>
</div>
</body>
</html>
