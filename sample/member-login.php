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
    $userId = !empty($_GET['userId']) ? $_GET['userId'] : null;
    if (empty($userId)) {
        $userId = !empty($_POST['userId']) ? $_POST['userId'] : null;
    }

    if (empty($userId)) {
        throw new \Exception('清算通用户ID不能为空');
    }
    $registerObj = new \trhui\business\Register();
    if (!$registerObj->hasUserId($userId)) {
        throw new \Exception('用户不存在');
    }

    $inputObj = new \trhui\data\MemberLogin();
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
    <title>会员自助登录</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<script type="text/javascript">
    var url = '<?= $tpam->getUrl() ?>';
    var data = <?=json_encode($res, JSON_UNESCAPED_UNICODE)?>;
    sendData(url, data);
</script>
</body>
</html>

