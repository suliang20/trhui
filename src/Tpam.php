<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:21
 */

namespace Trhui;

use Trhui\data\ToRegister;

class Tpam
{
    public $errors = array();

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
     * 接口版本号（默认为：1.0.0）
     * @var string
     */
    public $version = '1.0.0';
    /**
     * 时间戳
     * @var
     */
    public $date;

    /**
     * 签名，由merOrderId + merchantNo+date+params根据私钥生成如果有参数为null，签名串中应当做空字符串("")来处理
     * @var
     */
    private $sign;
    /**
     * 业务类型编号（采用接口名称interface后的字符）如注册：toRegister
     * @var
     */
    private $serverCode;
    /**
     * 业务参数，json格式
     * @var
     */
    private $params;


    public function __construct($merchantNo, $privateKeyPath, $publicKeyPath)
    {
        $this->merchantNo = $merchantNo;

        $this->rsaPrivateKeyPath = $privateKeyPath;
        $this->rsaPublicKeyPath = $publicKeyPath;
        $this->date = $_SERVER['REQUEST_TIME'];
        try {
            $this->rsaPrivateKey = file_get_contents($this->rsaPrivateKeyPath);
            $this->rsaPublicKey = file_get_contents($this->rsaPublicKeyPath);
        } catch (\Trhui\TpamException $e) {
            $this->addError('construct', $e->getMessage());
        }
    }


    public function toRegister(ToRegister $inpubObj, $merOrderId)
    {
        $url = $this->basePath . '/interface/toRegister';
        $this->serverCode = 'toRegister';

        try {
            $this->params = $inpubObj->toJson();
            if (!$this->params) {
                foreach ($inpubObj->errors as $error) {
                    throw new \Trhui\TpamException($error);
                }
            }
            if (!$this->sign) {
                throw new \Trhui\TpamException('未签名');
            }

            var_dump($this->getValues());
            exit;
        } catch (\Trhui\TpamException $e) {
            $this->addError('toRegister', $e->getMessage());
        }
        return false;
    }

    public function getValues()
    {
        return [
            'merOrderId' => $this->merOrderId,
            'merchantNo' => $this->merchantNo,
            'sign' => $this->sign,
            'serverCode' => $this->serverCode,
            'version' => $this->version,
            'params' => $this->params,
            'date' => $this->date,
        ];
    }

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
    private function sign($data)
    {
        try {
            //转换为openssl密钥，必须是经过pkcs8转换的私钥
            $res = openssl_get_privatekey($this->rsaPrivateKey);
            if (!$res) {
                throw new \Trhui\TpamException('转换密钥失败');
            }
            //调用openssl内置签名方法，生成签名$sign
            openssl_sign($data, $sign, $res);
            //释放资源
            openssl_free_key($res);
            //base64编码
//        $sign = base64_encode($sign);
            return $sign;
        } catch (\Trhui\TpamException $e) {
            $this->addError('sign', $e->getMessage());
        }
        return false;
    }

    /**
     * 生成签名
     * @return bool
     */
    public function MakeSign()
    {
        try {
            $string = $this->merOrderId . $this->merchantNo . $this->date . $this->params;
            $this->sign = $this->sign($string);
            if (!$this->sign) {
                throw new \Trhui\TpamException('生成签名失败');
            }
            return $this->sign;
        } catch (\Trhui\TpamException $e) {
            $this->addError('makeSign', $e->getMessage());
        }
        return false;
    }

    /**
     * 添加错误
     * @param $name
     * @param $errorMsg
     */
    public function addError($name, $errorMsg)
    {
        $this->errors[$name] = $errorMsg;
    }
}