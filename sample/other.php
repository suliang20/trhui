<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./core/init.php');
require_once '../vendor/autoload.php';

if (is_post()) {
    $result = [
        'error' => 0,
        'msg' => '提交错误',
    ];
    try {
        if (empty($_POST['button'])) {
            throw new \Exception('请求类型为空');
        }
        if (empty($_POST['userId'])) {
            throw new \Exception('用户ID不能为空');
        }

        switch ($_POST['button']) {
            case 'balance':
                $inputObj = new \trhui\data\GetBalance();
                $inputObj->SetUserId($_POST['userId']);

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
                $result['data'] = $resultRes['result'];
                break;

            case 'login':
                $inputObj = new \trhui\data\MemberLogin();
                $inputObj->SetUserId($_POST['userId']);

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
                $result['data']['businessData'] = $res;
                $result['data']['businessUrl'] = $tpam->getUrl();
                break;

            case 'withdraw':
                //  结算金额
                if (empty($_POST['amount'])) {
                    throw new \Exception('结算金额不能为空');
                }
                $amount = $_POST['amount'];
                //  是否需要审核
                $isNeedForAudit = !empty($_POST['is_need_for_audit']) ? $_POST['is_need_for_audit'] : 0;

                $inputObj = new \trhui\data\ToWithdraw();
                $inputObj->SetNotifyUrl(NOTIFY_URL);
                $inputObj->SetFrontUrl(FRONT_URL);

                //  获取商户用户ID
                $inputObj->SetUserId($_POST['userId']);
                $inputObj->SetAmount($amount);
                $inputObj->SetFeePayer(0);
                $inputObj->SetFeeToMerchant(0);
                $inputObj->SetIsNeedForAudit($isNeedForAudit);

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
                $result['data']['businessData'] = $res;
                $result['data']['businessUrl'] = $tpam->getUrl();
                break;

            case 'toAuthen':
                $inputObj = new \trhui\data\ToAuthen();
                $inputObj->SetNotifyUrl(NOTIFY_URL);
                $inputObj->SetFrontUrl(FRONT_URL);
                $inputObj->SetUserId($_POST['userId']);

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
                $result['data']['businessData'] = $res;
                $result['data']['businessUrl'] = $tpam->getUrl();
                break;
            default:
                throw new \Exception('不存在的请求类型');
        }

        $result['error'] = 1;
        $result['msg'] = '提交成功';
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
            <label for="userId">用户ID</label>
            <input type="text" name="userId" id="userId">
        </div>
        <button type="button" onclick="getBalance()">用户余额</button>
        <button type="button" onclick="goLogin()">用户登录</button>
        <button type="button" onclick="toAuthen()">用户认证</button>
    </form>
</div>

<div>
    <form action="" method="post" id="withdraw" name="trhuiForm">
        <div>
            <label for="userId">用户ID</label>
            <input type="text" name="userId" id="userId">
            <label for="amount">提现金额</label>
            <input type="text" name="amount" id="amount">
        </div>
        <button type="button" onclick="goWithdraw()">提现</button>
    </form>
</div>

</body>
</html>

<script>
  function getBalance () {
    var dataStr = ($('#trhuiForm').serialize())
    dataStr += '&button=balance'
    console.log(dataStr)

    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: dataStr,
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
          console.log(data.data)
          alert((data.data))
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  }

  function goLogin () {
    var dataStr = ($('#trhuiForm').serialize())
    dataStr += '&button=login'
    console.log(dataStr)

    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: dataStr,
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
          sendData(data.data.businessUrl, data.data.businessData)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  }

  function goWithdraw () {
    var dataStr = ($('#withdraw').serialize())
    dataStr += '&button=withdraw'
    console.log(dataStr)

    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: dataStr,
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
          sendData(data.data.businessUrl, data.data.businessData)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  }

  function toAuthen () {
    var dataStr = ($('#trhuiForm').serialize())
    dataStr += '&button=toAuthen'
    console.log(dataStr)

    $.ajax({
      cache: true,
      type: 'POST',
      url: window.location.href,//提交的URL
      data: dataStr,
      async: false,
      dataType: 'json',
      success: function (data) {
        if (data.error == 0) {
          alert(data.msg)
        } else if (data.error == 1) {
          sendData(data.data.businessUrl, data.data.businessData)
        } else {
          alert('数据异常')
        }
      },
      error: function (request) {
        alert('Connection error')
      }
    })
  }

</script>
