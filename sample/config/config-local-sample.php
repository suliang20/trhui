<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2019/7/31
 * Time: 9:28
 */

return [
    'private_key' => 'rsa/pkcs8_rsa_private_key.pem',
    'public_key' => 'rsa/tpamPublic.pem',

    'mer_chant_no' => 'test',
    'server_url' => 'http://cmbtest.trhui.com/',

    'notify_url' => 'http://test.com/back-result-local.php',
    'front_url' => 'http://test.com/front-result.php',

    'mer_order_id' => date('YmdHis'),
    'order_id' => date('YmdHis'),
];