<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/2/24
 * Time: 11:04
 */

require_once('./core/init.php');
require_once('../vendor/autoload.php');

$userInfo = [];
try {
    if (empty($_GET['userId'])) {
        throw new \Exception('用户ID为空');
    }
    $registerObj = new \trhui\business\Register();
    $userInfo = $registerObj->getUserByUserId($_GET['userId']);
    if (!$userInfo) {
        throw new \Exception('用户不存在');
    }
} catch (\Exception $e) {
}
echo json_encode($userInfo, JSON_UNESCAPED_UNICODE);
?>