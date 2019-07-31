<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once('../vendor/autoload.php');


//var_dump((new \trhui\business\Register())->getAll());exit;
if (is_post()) {
    $result = [
        'error' => 0,
        'msg' => '提交错误',
    ];
    try {
        do {
            $registerObj = new \trhui\business\Register();
            //  用户ID
            if (!isset($_POST['userId']) || !is_numeric($_POST['userId'])) {
                throw new \Exception('用户ID不存在');
            }
            $userId = $_POST['userId'];
            if (!$registerObj->hasUserId($userId)) {
                throw new \Exception('用户不存在');
            }
            //  认证类型
            if (!isset($_POST['accreditType']) || !is_numeric($_POST['accreditType'])) {
                throw new \Exception('没有选择认证类型');
            }
            $accreditType = $_POST['accreditType'];
            //  授权截止时间
            if (!isset($_POST['lastDate'])) {
                throw new \Exception('授权截止时间');
            }
            $lastDate = $_POST['lastDate'];
            //  单笔授权限额
            if (!isset($_POST['accreditAmountSingle']) || !is_numeric($_POST['accreditAmountSingle'])) {
                throw new \Exception('单笔授权限额');
            }
            $accreditAmountSingle = $_POST['accreditAmountSingle'];
            //  总授权金额
            if (!isset($_POST['accreditAmount']) || !is_numeric($_POST['accreditAmount'])) {
                throw new \Exception('总授权金额');
            }
            $accreditAmount = $_POST['accreditAmount'];
            //  单笔授权金额不能不大于总授权金额
            if ($accreditAmountSingle > $accreditAmount) {
                throw new \Exception('单笔授权金额不能不大于总授权金额');
            }

            $inputObj = new \trhui\data\AccreditNew();
            $inputObj->SetNotifyUrl(NOTIFY_URL);
            $inputObj->SetFrontUrl(FRONT_URL);

            $inputObj->SetUserId($userId);
            $inputObj->SetAccreditType($accreditType);
            $inputObj->SetLastDate($lastDate);
            $inputObj->SetAccreditAmountSingle($accreditAmountSingle);
            $inputObj->SetAccreditAmount($accreditAmount);

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
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>用户授权</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
    <script type="text/javascript" src="js/laydate/laydate.js"></script>
    <script type="text/javascript">
        laydate.render({
            elem: '#lastDate',
            format: 'yyyyMMdd',
//            min: '<?//=date('Y-m-d', time() + 3600 * 24)?>//'
            min: 1,
            btns: ['confirm'],
        });
    </script>
</head>
<body>
<div>
    <form action="" method="post" id="trhuiForm" name="trhuiForm">
        <div>
            <!-- 用户ID -->
            <?php if (isset($_GET['userId']) && (new \trhui\business\Register())->hasUserId($_GET['userId'])): ?>
                <input type="hidden" id="userId" name="userId" value="<?= $_GET['userId'] ?>">
            <?php else: ?>
                <label for="userId">授权用户</label>
                <select name="userId" id="userId">
                    <option value="">请选择用户</option>
                    <?php foreach ((new \trhui\business\Register())->getAll() as $key => $value): ?>
                        <?php if (isset($value['userId'])): ?>
                            <option value="<?= $value['userId'] ?>" <?= isset($_GET['mobile']) && $_GET['mobile'] == $value['mobile'] ? 'selected="selected"' : '' ?>><?= $value['mobile'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <!-- 授权类型 -->
            <div>
                <label for="accreditType">授权类型</label>
                <select name="accreditType" id="accreditType">
<!--                    <option value="">请选择类型</option>-->
                    <?php foreach (\trhui\data\AccreditNew::$ACCREDIT_TYPE as $type => $typeValue): ?>
                        <option value="<?= $type ?>"><?= $typeValue ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- 授权截止时间 -->
            <div>
                <label for="lastDate">授权截止时间</label>
                <input type="text" id="lastDate" name="lastDate">
            </div>
            <!-- 单笔授权限额 -->
            <div>
                <label for="accreditAmountSingle">单笔授权限额</label>
                <input type="text" id="accreditAmountSingle" name="accreditAmountSingle" value="0">
            </div>
            <!-- 总授权金额 -->
            <div>
                <label for="accreditAmount">总授权金额</label>
                <input type="text" id="accreditAmount" name="accreditAmount" value="10000">
            </div>
        </div>
        <button type="button" id="trhuiSubmit">授权</button>
    </form>
</div>
<script type="text/javascript">

    //  切换用户
    $("#userId").change(function () {
        var userId = $("#userId").val()
        getAccreditInfo(userId)
    })

    //  更新授权类型
    $("#accreditType").change(function () {
        $("#lastDate").val('')
        $("#accreditAmountSingle").val(0)
        $("#accreditAmount").val(10000)
        var userId = $("#userId").val()
        getAccreditInfo(userId)
    })

    /**
     * 获取用户授权数据
     * @param userId
     */
    function getAccreditInfo(userId) {
        var accreditType = $('#accreditType').val();
        $.get(
            'user-info.php',
            {userId: userId},
            function (data) {
                if (data.accredit != undefined) {
                    $.each(data.accredit, function (name, value) {
                        if (accreditType == name) {
                            $("#lastDate").val(value.lastDate)
                            $("#accreditAmountSingle").val(value.accreditAmountSingle)
                            $("#accreditAmount").val(value.accreditAmount)
                        }
                    })
                }
            },
            "json",
        )
    }

    <?php if(!empty($_GET['userId'])):?>
    getAccreditInfo(<?=$_GET['userId']?>);
    <?php endif;?>

</script>
</body>
</html>

