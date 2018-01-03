<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:21
 */

namespace Trhui;

class Tpam
{
    /**
     * 服务器地址
     * @var string
     */
    public $basePath = 'ttp://cmbtest.trhui.com/tpam/service/';

    public $rsaPrivateKeyPath;
    public $rsaPublicKeyPath;
    public $tpamPublicKeyPath;

    public $rsaPrivateKey;
    public $rsaPublicKey;
    public $tpamPublicKey;

    /**
     * 商户号
     * @var
     */
    public $merchantNo;
    /**
     * 订单号
     * @var
     */
    public $merOrderId;
    /**
     * 签名，由merOrderId + merchantNo+date+params根据私钥生成如果有参数为null，签名串中应当做空字符串("")来处理
     * @var
     */
    public $sign;
    /**
     * 业务类型编号（采用接口名称interface后的字符）如注册：toRegister
     * @var
     */
    public $serverCode;
    /**
     * 接口版本号（默认为：1.0.0）
     * @var string
     */
    public $version = '1.0.0';


    public function test($data)
    {
        $encrypted = "";
        $decrypted = "";

        $this->tpamPublicKey = file_get_contents($this->tpamPublicKeyPath);
        $this->rsaPrivateKey = file_get_contents($this->rsaPrivateKeyPath);
        $this->rsaPublicKey = file_get_contents($this->rsaPublicKeyPath);

        var_dump($this->sign($data));
        exit;

        openssl_private_encrypt($data, $encrypted, $this->rsaPrivateKey);//私钥加密
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        echo $encrypted, "\n";

        openssl_public_decrypt(base64_decode($encrypted), $decrypted, $this->rsaPublicKey);//私钥加密的内容通过公钥可用解密出来
        echo $decrypted, "\n";
    }

    /**
     * $data待签名数据
     * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     * return Sign签名
     */
    public function sign($data)
    {
        //转换为openssl密钥，必须是经过pkcs8转换的私钥
        $res = openssl_get_privatekey($this->rsaPrivateKey);
        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res);
        //释放资源
        openssl_free_key($res);
        //base64编码
//        $sign = base64_encode($sign);
        return $sign;
    }
}