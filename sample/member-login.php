<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

$result = [
    'error' => 0,
    'msg' => '提交错误',
];
try {
    if (true) {
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

//        if (!$postRes = $tpam->postCurl($res, $tpam->getUrl())) {
//            foreach ($tpam->errors as $error) {
//                throw new \Exception($error['errorMsg']);
//            }
//        }
//        var_dump($postRes);exit;
    }

//    $postRes = <<<JSON
//{"resultObj":{"merOrderId":"20180208165607","platformOrderId":"TK20180208002289","userId":"167","refundType":"1","amount":1,"status":"0"},"result":"{\"amount\":1,\"status\":\"0\",\"userId\":\"167\",\"merOrderId\":\"20180208165607\",\"platformOrderId\":\"TK20180208002289\",\"parameter1\":null,\"refundType\":\"1\"}","sign":"G7JPjZcNaxY8jhhdbJWeurn+Got5MmTXCZ9BeUM1O\/u7944yS4YNaYqb0tZG8EJW3rhJ8XGSWbBTy7+i\/QdGcNj0FMqDy0F\/5HHm5UcrehFI1Ib4X4JU7vZ6GA4\/\/zwV9qc0KvJBbuZ2PBq6P1QsFhLC\/vIjbu2tXlW0EjPS5cY=","code":"100","msg":"操作成功","date":"1518080167975","version":"1.0"}
//JSON;
//    $postRes = json_decode($postRes, true);
//    var_dump($postRes);exit;

//    $resultObj = new \trhui\extend\Results();
//    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
//    $resultRes = $resultObj->handle($postRes);
//
//    if (!empty($resultObj->errors)) {
//        foreach ($resultObj->errors as $error) {
//            throw new \Exception($error['errorMsg']);
//        }
//    }

//    echo (json_encode($postRes, JSON_UNESCAPED_UNICODE));
//    echo PHP_EOL;

//    $result['error'] = 1;
//    $result['msg'] = '提交成功';
//    $result['data'] = $resultRes;
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}
//echo json_encode($result, JSON_UNESCAPED_UNICODE);
//exit;

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

