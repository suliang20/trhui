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
            if (!\trhui\business\Register::chkMobile($_POST['newPhone'])) {
                throw new \trhui\TpamException('手机号格式错误');
            }
            $mobile = $_POST['newPhone'];
            $registerObj = new \trhui\business\Register();
            if ($registerObj->hasMobile($mobile)) {
                throw new \trhui\TpamException('手机号已注册');
            }
            if (empty($_POST['userId'])) {
                throw new \trhui\TpamException('数据错误');
            }
            $userId = $_POST['userId'];
            if (!$registerObj->hasUserId($userId)) {
                throw new \Exception('清算通用户ID不存在');
            }


            $inputObj = new \trhui\data\ModifyPhone();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            //  获取商户用户ID
            $inputObj->SetUserId($userId);
            $inputObj->SetNewPhone($mobile);

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
    <title>用户手机修改示例</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <label for="mobile">用户新手机号</label>
            <input type="text" name="newPhone" id="newPhone">
            <input type="hidden" name="userId" id="userId" value="<?= $_GET['userId'] ?>">
        </div>
        <button type="button" id="trhuiSubmit">提交修改</button>
        <a href="register.php">返回注册页面</a>
    </form>
</div>
</body>
</html>

