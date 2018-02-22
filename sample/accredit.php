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
                throw new \trhui\TpamException('认证用户ID不存在');
            }
            $inputObj = new \trhui\data\Accredit();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);
            $inputObj->SetUserId($_POST['payee_user_id']);

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
    <title>用户授权</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <form action="pay.php" method="post" id="payForm" name="payForm">
        <div>
            <label for="payeeUserId">授权用户</label>
            <select name="payee_user_id" id="payeeUserId">
                <option value="">请选择用户</option>
                <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                    <?php if ($value['authed'] != 1 || (isset($value['authedType']) && ($value['authedType'] == 0 || $value['authedType'] == 1))) continue; ?>
                    <?php if (isset($value['userId'])): ?>
                        <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="button" id="submitPay">授权</button>
    </form>
</div>
<div>
    <table border="2">
        <tr>
            <th>认证状态</th>
            <th>认证类型</th>
            <th>清算平台用户ID</th>
            <th>商户平台用户ID</th>
            <th>订单号</th>
            <th>手机号</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ((new \trhui\business\Register())->getAll() as $item): ?>
            <?php if (isset($item['userId'])): ?>
                <?php if ($item['authed'] != 1) continue; ?>
                <tr>
                    <td><?= isset($item['authed']) && $item['authed'] == 1 ? '已认证' : "<a href='authen.php?mobile={$item['mobile']}'>未认证</a>" ?></td>
                    <td><?= isset($item['authenType']) ? ($item['authenType'] == 1 ? '企业认证' : '个人认证') : '未认证' ?></td>
                    <td><?= isset($item['userId']) ? $item['userId'] : '' ?></td>
                    <td><?= isset($item['merUserId']) ? $item['merUserId'] : '' ?></td>
                    <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                    <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
                    <td><?= isset($item['register_time']) ? date('Y-m-d H:i:s', substr($item['register_time'], 0, -3)) : '' ?></td>
                    <td><a href="pay.php?mobile=<?= $item['mobile'] ?>">支付</a></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

