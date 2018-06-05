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
            //  结算清算通用户ID
            if (empty($_POST['userId'])) {
                throw new \Exception('数据错误');
            }
            $registerObj = new \trhui\business\Register();
            $userId = $_POST['userId'];
            if (!$registerObj->hasUserId($userId)) {
                throw new \Exception('清算通用户ID不存在');
            }
            //  结算金额
            if (empty($_POST['amount'])) {
                throw new \Exception('结算金额不能为空');
            }
            $amount = $_POST['amount'];
            //  是否需要审核
            $isNeedForAudit = !empty($_POST['is_need_for_audit']) ? $_POST['is_need_for_audit'] : 0;

            $inputObj = new \trhui\data\ToWithdraw();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            //  获取商户用户ID
            $inputObj->SetUserId($userId);
            $inputObj->SetAmount($amount);
            $inputObj->SetFeePayer(0);
            $inputObj->SetFeeToMerchant(0);
            $inputObj->SetIsNeedForAudit($isNeedForAudit);

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
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户手机修改示例</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <label for="amount">结算金额</label>
            <input type="text" name="amount" id="amount" value="0">
        </div>
        <div>
            <label for="isNeedForAudit">是否需要审核</label>
            <select name="isNeedForAudit" id="isNeedForAudit">
                <option value="0">否</option>
                <option value="1">是</option>
            </select>
        </div>
        <input type="hidden" name="userId" id="userId" value="<?= $_GET['userId'] ?>">
        <button type="button" id="trhuiSubmit">提交结算</button>
    </form>
</div>
<div>
    <a href="register.php">返回注册页面</a>
</div>
</body>
</html>

