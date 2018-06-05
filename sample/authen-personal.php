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
        if (!isset($_POST['userId']) || !is_numeric($_POST['userId'])) {
            throw new \Exception('授权用户ID不存在');
        }
        $inputObj = new \trhui\data\PersonalCertificate();
        $inputObj->SetNotifyUrl(NOTIFY_URL);

        $inputObj->SetUserId($_POST['userId']);
        $inputObj->SetRealName($_POST['realName']);
        $inputObj->SetCardNo($_POST['cardNo']);
        $inputObj->SetBankCard($_POST['bankCard']);
        $inputObj->SetMobile($_POST['mobile']);
        $inputObj->SetCertificationType($_POST['certificationType']);
        $inputObj->SetCardFrontUrl($_POST['cardFrontUrl']);
        $inputObj->SetCardBackUrl($_POST['cardBackUrl']);
        $inputObj->SetOrganDocumentsUrl($_POST['organDocumentsUrl']);

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

        if ($postRes['code'] != 100) {
            throw new \Exception($postRes['msg']);
        }
        $result['error'] = 1;
        $result['msg'] = $postRes['msg'];
    } catch (\Exception $e) {
        $result['msg'] = $e->getMessage();
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户实名认证</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <label for="userId">认证用户</label>
            <select name="userId" id="userId">
                <option value="">请选择用户</option>
                <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                    <?php if ($value['authed'] == 1) continue; ?>
                    <?php if (isset($value['userId'])): ?>
                        <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="realName">真实姓名</label>
            <input type="text" name="realName" id="realName" value="test">
        </div>
        <div>
            <label for="cardNo">身份证号码</label>
            <input type="text" name="cardNo" id="cardNo" value="340201190001012632">
        </div>
        <div>
            <label for="bankCard">银行卡号</label>
            <input type="text" name="bankCard" id="bankCard" value="6221558834567890">
        </div>
        <div>
            <label for="mobile">手机号码</label>
            <input type="text" name="mobile" id="mobile" value="13000000001">
        </div>
        <div>
            <label for="certificationType">认证类型</label>
            <select name="certificationType" id="ceartificationType">
                <option value="0">内地居民</option>
                <option value="1">港澳台及外籍居民</option>
            </select>
        </div>
        <div>
            <label for="cardFrontUrl">身份证正面</label>
            <input type="text" name="cardFrontUrl" id="cardFrontUrl"
                   value="http://img02.tooopen.com/images/20160509/tooopen_sy_161967094653.jpg">
        </div>
        <div>
            <label for="cardFrontUrl">身份证反面</label>
            <input type="text" name="cardBackUrl" id="cardBackUrl"
                   value="http://img02.tooopen.com/images/20160509/tooopen_sy_161967094653.jpg">
        </div>
        <div>
            <label for="organDocumentsUrl">机构证件照片</label>
            <input type="text" name="organDocumentsUrl" id="organDocumentsUrl"
                   value="http://img02.tooopen.com/images/20160509/tooopen_sy_161967094653.jpg">
        </div>
        <button type="button" id="trhuiAjaxSubmit">认证</button>
    </form>
</div>
</body>
</html>

