<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');

$result = [
    'error' => 0,
    'msg' => '提交错误',
];
try {
    if (empty($_GET['userId'])) {
        throw new \Exception('数据错误');
    }
    $userId = $_GET['userId'];
    $registerObj = new \trhui\business\Register();
    if (!$registerObj->hasUserId($userId)) {
        throw new \Exception('清算通用户ID不存在');
    }

    $inputObj = new \trhui\data\ModifyPassword();
    $inputObj->SetFrontUrl(FRONT_URL);
    $inputObj->SetUserId($userId);

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
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>交易密码修改示例</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<?php if ($result['error'] == 0): ?>
    <p><?= $result['msg'] ?></p>
<?php endif; ?>
<script type="text/javascript">
    var url = '<?= $tpam->getUrl() ?>';
    var data = <?=json_encode($res, JSON_UNESCAPED_UNICODE)?>;
    sendData(url, data);
</script>
</body>
</html>

