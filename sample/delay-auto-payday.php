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
    if (empty($_POST['merOrderId'])) {
        throw new \trhui\TpamException('交易订单号不能为空');
    }
    $merOrderId = $_POST['merOrderId'];
    $registerObj = new \trhui\business\PayOrder();
    if (!$orderInfo = $registerObj->getOneByMerOrderId($merOrderId)) {
        throw new \trhui\TpamException('交易详情不存在');
    }

    $inputObj = new \trhui\data\DelayAutoPayday();
    $inputObj->SetNotifyUrl(NOTIFY_URL);
    $inputObj->SetFrontUrl(FRONT_URL);
    $inputObj->SetOriginalPlatformOrderId($orderInfo['platformOrderId']);
    $inputObj->SetOriginalOrderId($orderInfo['orderId']);
    $inputObj->SetUserId($orderInfo['payerUserId']);
    $inputObj->SetDays(1);

    $tpam = new \trhui\Tpam();
    $tpam->serverUrl = SERVER_URL;
    $tpam->merchantNo = MER_CHANT_NO;
    $tpam->rsaPrivateKeyPath = PRIVATE_KEY_PATH;
    $res = $tpam->frontInterface($inputObj, MER_ORDER_ID);
    if (!$res) {
        foreach ($tpam->errors as $error) {
            throw new \trhui\TpamException($error['errorMsg']);
        }
    }

    if (!$postRes = $tpam->postCurl($res, $tpam->getUrl())) {
        foreach ($tpam->errors as $error) {
            throw new \trhui\TpamException($error['errorMsg']);
        }
    }

    $resultObj = new \trhui\Results();
    $resultObj->tpamPublicKeyPath = PUBLIC_KEY_PATH;
    $resultRes = $resultObj->handle($postRes);
    if (!$resultRes) {
        foreach ($resultObj->errors as $error) {
            throw new \trhui\TpamException($error['errorMsg']);
        }
    }

    $result['error'] = 1;
    $result['msg'] = '提交成功';
    $result['data'] = $resultRes;
} catch (\trhui\TpamException $e) {
    $result['msg'] = $e->getMessage();
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit;
?>

