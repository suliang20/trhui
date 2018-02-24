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
    <title>用户注册示例</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
    <style>
        table {
            border-collapse: collapse;
            font-size: 6px;
        }

        table, th, td {
            border: 1px solid black;
            font-size: 6px;
        }
    </style>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <label for="mobile">用户手机号</label>
            <input type="text" name="mobile" id="mobile">
        </div>
        <button type="button" id="trhuiSubmit">提交注册</button>
        <a href="pay.php">支付</a>
    </form>
</div>
<div>
    <a href="register-list.php">注册列表</a>
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
                <tr>
                    <td><?= isset($item['authed']) && $item['authed'] == 1 ? '已认证' : "<a href='authen.php?mobile={$item['mobile']}'>未认证</a>" ?></td>
                    <td><?= isset($item['authenType']) ? ($item['authenType'] == 1 ? '企业认证' : '个人认证') : '未认证' ?></td>
                    <td><?= isset($item['userId']) ? $item['userId'] : '' ?></td>
                    <td><?= isset($item['merUserId']) ? $item['merUserId'] : '' ?></td>
                    <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                    <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
                    <td><?= isset($item['register_time']) ? date('Y-m-d H:i:s', substr($item['register_time'], 0, -3)) : '' ?></td>
                    <td>
                        <a href="pay.php?mobile=<?= $item['mobile'] ?>">支付</a>
                        <a href="modify-password.php?userId=<?= $item['userId'] ?>">修改交易密码</a>
                        <a href="modify-phone.php?userId=<?= $item['userId'] ?>">修改手机号</a>
                        <a href="member-login.php?userId=<?= $item['userId'] ?>">会员自助登录</a>
                        <a href="accredit-new.php?userId=<?= $item['userId'] ?>">授权</a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

