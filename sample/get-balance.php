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
    $inputObj = new \trhui\data\GetBalance();
    $inputObj->SetUserId($_GET['userId']);

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
//    echo json_encode($res);exit;
    $postRes = $tpam->postCurl($res, $tpam->getUrl());
    if (!$postRes) {
        foreach ($tpam->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }
//    var_dump($postRes);exit;

    $resultObj = new \trhui\extend\AccountResults();
    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
    $resultRes = $resultObj->handle($postRes);

    if (!empty($resultObj->errors)) {
        foreach ($resultObj->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }

    $result['error'] = 1;
    $result['msg'] = '提交成功';
    $result['data'] = $resultRes;
} catch (\Exception $e) {
    $result['msg'] = $e->getMessage();
}

var_dump($result);
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>会员资金查询</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<div>

</div>
</body>
</html>

