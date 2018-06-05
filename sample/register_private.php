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
                throw new \Exception('手机号格式错误');
            }
            $mobile = $_POST['mobile'];
            $registerObj = new \trhui\business\Register();
            if ($registerObj->hasMobile($mobile)) {
                throw new \Exception('手机号已注册');
            }

            $inputObj = new \trhui\data\ToPrivateRegister();
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
    <title>用户注册示例</title>
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
            <label for="mobile">用户手机号</label>
            <input type="text" name="mobile" id="mobile">
        </div>
        <button type="button" id="trhuiSubmit">提交注册</button>
    </form>
</div>

<?php require_once 'user-list.php' ?>

</body>
</html>

