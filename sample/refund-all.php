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
    $merOrderId = !empty($_GET['merOrderId']) ? $_GET['merOrderId'] : null;
    if (empty($merOrderId)) {
        $merOrderId = !empty($_POST['merOrderId']) ? $_POST['merOrderId'] : null;
    }
    if (empty($merOrderId)) {
        throw new \Exception('商户订单号不能为空');
    }

    $transferObj = new \trhui\business\PayRequestOrder();
    $transferOrder = $transferObj->getTransferOrder($merOrderId);
    if (!$transferOrder) {
        throw new \Exception('交易订单不存在');
    }

    $inputObj = new \trhui\data\RefundAll();
    $inputObj->SetNotifyUrl(NOTIFY_URL);
    $inputObj->SetFrontUrl(FRONT_URL);

    $inputObj->SetOriginalPlatformOrderId($transferOrder['platformOrderId']);
    $inputObj->SetOriginalMerOrderId($transferOrder['merOrderId']);
    $inputObj->SetUserId($transferOrder['payerUserId']);

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

    if (!$postRes = $tpam->postCurl($res, $tpam->getUrl())) {
        foreach ($tpam->errors as $error) {
            throw new \Exception($error['errorMsg']);
        }
    }

//    var_dump($postRes);exit;

    $resultObj = new \trhui\extend\Results();
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
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit;

?>
