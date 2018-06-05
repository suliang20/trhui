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
        $inputObj = new \trhui\data\EnterpriseCertificate();
        $inputObj->SetNotifyUrl(NOTIFY_URL);

        $inputObj->SetUserId($_POST['userId']);
        $inputObj->SetOrganCode($_POST['organCode']);
        $inputObj->SetBusinessLicense($_POST['businessLicense']);
        $inputObj->SetLegalPersonName($_POST['legalPersonName']);
        $inputObj->SetCorporateId($_POST['corporateId']);
        $inputObj->SetMobile($_POST['mobile']);
        $inputObj->SetAcctNo($_POST['acctNo']);
        $inputObj->SetAcctName($_POST['acctName']);
        $inputObj->SetBranchNo($_POST['branchNo']);
        $inputObj->SetOrganType($_POST['organType']);
        $inputObj->SetOrganCode($_POST['organCode']);
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
    <title>用户企业认证</title>
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
            <label for="organCode">组织机构代码</label>
            <input type="text" name="organCode" id="organCode" value="test">
        </div>
        <div>
            <label for="businessLicense">组织机构全称</label>
            <input type="text" name="businessLicense" id="businessLicense" value="test">
        </div>
        <div>
            <label for="legalPersonName">法人姓名</label>
            <input type="text" name="legalPersonName" id="legalPersonName" value="test">
        </div>
        <div>
            <label for="corporateId">法人证件号</label>
            <input type="text" name="corporateId" id="corporateId" value="340201190001012632">
        </div>
        <div>
            <label for="mobile">手机号码</label>
            <input type="text" name="mobile" id="mobile" value="13000000001">
        </div>
        <div>
            <label for="acctNo">对公帐号</label>
            <input type="text" name="acctNo" id="acctNo" value="6221558834567890">
        </div>
        <div>
            <label for="acctName">对公户名</label>
            <input type="text" name="acctName" id="acctName" value="6221558834567890">
        </div>
        <div>
            <label for="branchNo">开户网点联行号</label>
            <input type="text" name="branchNo" id="branchNo" value="6221558834567890">
        </div>
        <div>
            <label for="organType">机构类型</label>
            <select name="organType" id="organType">
                <option value="0">营利性组织</option>
                <option value="1">非营利性组织</option>
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

