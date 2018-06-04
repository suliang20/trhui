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
            if (!isset($_POST['payee_user_id']) || !is_numeric($_POST['payee_user_id'])) {
                throw new \trhui\TpamException('授权用户ID不存在');
            }
            $inputObj = new \trhui\data\ToAuthen();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);
            $inputObj->SetUserId($_POST['payee_user_id']);
            $inputObj->SetAuthenType($_POST['authed_type']);

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
    <title>用户实名认证</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <label for="payeeUserId">认证用户</label>
            <select name="payee_user_id" id="payeeUserId">
                <option value="">请选择用户</option>
                <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                    <?php if ($value['authed'] == 1) continue; ?>
                    <?php if (isset($value['userId'])): ?>
                        <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="realName">真实姓名</label>
            <input type="text" name="realName" id="realName">
        </div>
        <div>
            <label for="cardNo">身份证号码</label>
            <input type="text" name="cardNo" id="cardNo">
        </div>
        <div>
            <label for="bankCard">银行卡号</label>
            <input type="text" name="bankCard" id="bankCard">
        </div>
        <div>
            <label for="mobile">手机号码</label>
            <input type="text" name="mobile" id="mobile">
        </div>
        <div>
            <label for="certificationType">认证类型</label>
            <select name="certificationType" id="ceartificationType">
                <option value="0">内地居民</option>
                <option value="1">港澳台及外籍居民</option>
            </select>
        </div>
        <div>
            <label for="cardFrontUrl">身份证正面</label>
            <input type="text" name="cardFrontUrl" id="cardFrontUrl">
        </div>
        <div>
            <label for="cardFrontUrl">身份证反面</label>
            <input type="text" name="cardBackUrl" id="cardBackUrl">
        </div>
        <div>
            <label for="organDocumentsUrl">身份证反面</label>
            <input type="text" name="organDocumentsUrl" id="organDocumentsUrl">
        </div>
        <button type="button" id="trhuiSubmit">认证</button>
    </form>
</div>
</body>
</html>

