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

            $tpam = new \trhui\extend\TpamExtend();
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
    <form action="pay.php" method="post" id="payForm" name="payForm">
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
            <label for="payeeUserId">认证类型</label>
            <select name="authed_type" id="authedType">
                <option value="0">个人认证</option>
                <option value="1">企业认证</option>
            </select>
        </div>
        <button type="button" id="submitPay">认证</button>
    </form>
</div>
</body>
</html>

