<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:29
 */
require_once('./config.php');
require '../vendor/autoload.php';

$registerObj = new \trhui\business\Register();
$registers = $registerObj->getAll();
//var_dump($registers);exit;

?>

<html>
<head>
    <title>注册列表</title>
    <?php
    require_once "common-js-style.php";
    ?>
</head>

<body>
<?php
require_once "common-link.php";
?>
<p><a href="register.php">注册</a></p>
<div>
    <table border="2">
        <tr>
            <th>认证状态</th>
            <th>认证类型</th>
            <th>清算平台用户ID</th>
            <th>商户平台用户ID</th>
            <th>订单号</th>
            <th>手机号</th>
            <th>注册时间</th>
            <th>操作</th>
            <th>结算</th>
        </tr>
        <?php foreach ((new \trhui\business\Register())->getAll() as $item): ?>
            <?php if (isset($item['userId'])): ?>
                <tr>
                    <td><?= isset($item['authed']) && $item['authed'] == 1 ? '已认证' : "<a href='authen.php?mobile={$item['mobile']}'>未认证</a>" ?></td>
                    <td><?= isset($item['authenType']) ? ($item['authenType'] == 1 ? '企业认证' : '个人认证') : '未认证' ?></td>
                    <td><?= isset($item['userId']) ? $item['userId'] : '' ?></td>
                    <td><?= isset($item['merUserId']) ? $item['merUserId'] : '' ?></td>
                    <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                    <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
                    <td><?= isset($item['register_time']) ? date('Y-m-d H:i:s', substr($item['register_time'], 0, -3)) : '' ?></td>
                    <td>
                        <a href="pay.php?mobile=<?= $item['mobile'] ?>">支付</a>
                        <a href="modify-password.php?userId=<?= $item['userId'] ?>">修改交易密码</a>
                        <a href="modify-phone.php?userId=<?= $item['userId'] ?>">修改手机号</a>
                        <a href="member-login.php?userId=<?= $item['userId'] ?>">会员自助登录</a>
                        <a href="accredit-new.php?userId=<?= $item['userId'] ?>">授权</a>
                        <a href="get-balance.php?userId=<?= $item['userId'] ?>">帐户查询</a>
                    </td>
                    <td>
                        <a href="to-withdraw.php?userId=<?= $item['userId'] ?>">结算</a>
                        <a href="to-withdraw-list.php?userId=<?= $item['userId'] ?>">结算列表</a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

