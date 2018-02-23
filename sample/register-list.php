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
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>注册列表</title>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/trhui.js"></script>
</head>

<body>
<div>
    <table border="2">
        <tr>
            <th>授权状态</th>
            <th>清算平台用户ID</th>
            <th>商户平台用户ID</th>
            <th>订单号</th>
            <th>手机号</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($registers as $item): ?>
            <?php if (isset($item['userId'])): ?>
                <tr>
                    <td><?= isset($item['authed']) ? $item['authed'] : '' ?></td>
                    <td><?= isset($item['userId']) ? $item['userId'] : '' ?></td>
                    <td><?= isset($item['merUserId']) ? $item['merUserId'] : '' ?></td>
                    <td><?= isset($item['merOrderId']) ? $item['merOrderId'] : '' ?></td>
                    <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
                    <td><?= isset($item['register_time']) ? date('Y-m-d H:i:s', substr($item['register_time'], 0, -3)) : '' ?></td>
                    <td>
                        <a href="pay.php?mobile=<?= $item['mobile'] ?>">支付</a>
                        <a href="modify-phone.php?userId=<?= $item['userId'] ?>">修改手机号</a>
                        <a href="member-login.php?userId=<?= $item['userId'] ?>">会员自助登录</a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>

