<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:21
 */

namespace trhui;

use trhui\data\Data;
use trhui\data\DataBase;
use trhui\data\ToRegister;

class Tpam extends Data
{
    public $errors = array();

    /**
     * 服务器地址
     * @var string
     */
    public $serverUrl;
    /**
     * 商户私钥路径
     * @var
     */
    public $rsaPrivateKeyPath;
    /**
     * 商户私钥
     * @var
     */
    public $rsaPrivateKey;
    public $url;


    /**
     * 商户号
     * @var
     */
    public $merchantNo;
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
     * 代理主机
     * @var string
     */
    public $curlProxyHost = '0.0.0.0';
    /**
     * 代理端口
     * @var int
     */
    public $curlProxyPort = 0;
    /**
     * 证书地址
     * @var
     */
    public $sslCertPath;
    /**
     * 证书KEY地址
     * @var
     */
    public $sslKeyPath;


    /**
     * 订单号(必填)
     * @var
     */
    public $merOrderId;
    /**
     * 签名，由merOrderId + merchantNo+date+params根据私钥生成如果有参数为null，签名串中应当做空字符串("")来处理(必填)
     * @var
     */
    public $sign;
    /**
     * 业务类型编号（采用接口名称interface后的字符）如注册：toRegister(必填)
     * @var
     */
    public $serverCode;
    /**
     * 业务参数，json格式(必填)
     * @var
     */
    public $params;
    /**
     * 扩展数据
     * @var
     */
    public $extendData = [];

    public function __construct()
    {
        $this->date = $_SERVER['REQUEST_TIME'];
    }

    /**
     * 前台接口
     * @param ToRegister $inpubObj
     * @param $merOrderId
     * @return bool|string
     */
    public function frontInterface(DataBase $inputObj, $merOrderId)
    {
        try {
            if (!$this->serverUrl) {
                throw new TpamException('服务地址不为空');
            }
            if (!$serverInterface = $inputObj->getServerInterface()) {
                throw new TpamException('服务接口不为空');
            }
            if (!$serverCode = $inputObj->getServerCode()) {
                throw new TpamException('业务类型编号不为空');
            }
            $this->url = $this->serverUrl . $serverInterface;
            $this->serverCode = $serverCode;

            if (empty($merOrderId)) {
                throw new TpamException('订单号不能为空');
            }

            $this->merOrderId = $merOrderId;
            $this->params = $inputObj->toJson();
            if (!$this->params) {
                $this->errors = array_merge($this->errors, $inputObj->errors);
                throw new TpamException('获取业务参数失败');
            }
            if (!$data = $this->getValues()) {
                throw new TpamException('JSON数据为空');
            }

            //  其他处理
            if (!$this->Process()) {
                throw new TpamException('请求处理失败');
            }

            return $data;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 请求处理
     */
    public function Process()
    {
        return true;
    }

    /**
     * 获取接口请求公共参数
     * @return array
     */
    public function getValues()
    {
        try {
            if (empty($this->date)) {
                throw new TpamException('时间戳为空');
            }
            if (empty($this->merOrderId)) {
                throw new TpamException('商户订单号不能为空');
            }
            if (empty($this->merchantNo)) {
                throw new TpamException('商户号不能为空');
            }

            if (empty($this->serverCode)) {
                throw new TpamException('业务类型为设置');
            }
            if (empty($this->version)) {
                throw new TpamException('版本号不能为空');
            }
            if (empty($this->params)) {
                throw new TpamException('业务参数不能为空');
            }
            //  生成签名
            if (!$this->MakeSign()) {
                throw new TpamException('签名失败');
            }
            if (!$this->sign) {
                //  生成签名
                if (!$this->MakeSign()) {
                    throw new TpamException('签名失败');
                }
            }

            return [
                'merOrderId' => $this->merOrderId,
                'merchantNo' => $this->merchantNo,
                'sign' => $this->sign,
                'serverCode' => $this->serverCode,
                'version' => $this->version,
                'params' => $this->params,
                'date' => $this->date,
                'extendData' => $this->extendData,
            ];
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 输出JSON数据
     * @return string
     */
    public function toJson()
    {
        try {
            $values = $this->getValues();
            if (!$values) {
                throw new TpamException('获取参数值失败');
            }
            return json_encode($values);
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
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
     * 获取接口URL
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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
            if (!file_exists($this->rsaPrivateKeyPath)) {
                throw new TpamException('私钥文件不存在');
            }
            $this->rsaPrivateKey = @file_get_contents($this->rsaPrivateKeyPath);
            if (empty($this->rsaPrivateKey)) {
                throw new TpamException('私钥不能为空');
            }
            //转换为openssl密钥，必须是经过pkcs8转换的私钥
            $res = openssl_get_privatekey($this->rsaPrivateKey);
            if (!$res) {
                throw new TpamException('转换密钥失败');
            }
            //调用openssl内置签名方法，生成签名$sign
            openssl_sign($data, $sign, $res);
            //释放资源
            openssl_free_key($res);
            //base64编码
            $sign = base64_encode($sign);
            return $sign;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 生成签名
     * @return bool
     */
    public function MakeSign($merOrderId = null, $merchantNo = null, $date = null, $params = null)
    {
        try {
            if (!empty($merOrderId)) {
                $this->merOrderId = $merOrderId;
            }
            if (!empty($merchantNo)) {
                $this->merchantNo = $merchantNo;
            }
            if (!empty($date)) {
                $this->date = $date;
            }
            if (!empty($params)) {
                $this->params = $params;
            }
            $string = $this->merOrderId . $this->merchantNo . $this->date . $this->params;
            $this->sign = $this->sign($string);
            if (!$this->sign) {
                throw new TpamException('生成签名失败');
            }
            return $this->sign;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param string $xml 需要post的xml数据
     * @param string $url url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second url执行超时时间，默认30s
     * @throws WxPayException
     */
    public function postCurl($requestData, $url, $useCert = false, $second = 30)
    {
        try {
            $ch = curl_init();
            //设置超时
            curl_setopt($ch, CURLOPT_TIMEOUT, $second);

            //如果有配置代理这里就设置代理
            if ($this->curlProxyHost != "0.0.0.0" && $this->curlProxyPort != 0) {
                curl_setopt($ch, CURLOPT_PROXY, $this->curlProxyHost);
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->curlProxyPort);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
            //设置header
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            //要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            if ($useCert == true) {
                //设置证书
                //使用证书：cert 与 key 分别属于两个.pem文件
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, $this->sslCertPath);
                curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLKEY, $this->sslKeyPath);
            }
            //post提交方式
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
            //运行curl
            $data = curl_exec($ch);
            //返回结果
            if (!$data) {
                $error = curl_errno($ch);
                curl_close($ch);
                throw new TpamException("curl出错，错误码:$error");
            }
            curl_close($ch);
            return json_decode($data, true);
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}