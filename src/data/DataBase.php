<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:18
 */

namespace Trhui\data;

class DataBase
{
    public $errors = array();
    protected $values = array();
    protected $params = array();

    //  TODO    订单号, 商户平台全局唯一的编号

    public function SetMerOrderId($value)
    {
        $this->values['merOrderId'] = $value;
    }

    public function GetMerOrderId()
    {
        return $this->values['merOrderId'];
    }

    public function IsMerOrderIdSet()
    {
        return array_key_exists('merOrderId', $this->values) && !empty($this->values['merOrderId']);
    }

    //  TODO    商户号

    public function SetMerchantNo($value)
    {
        $this->values['merchantNo'] = $value;
    }

    public function GetMerchantNo()
    {
        return $this->values['merchantNo'];
    }

    public function IsMerchantNoSet()
    {
        return array_key_exists('merchantNo', $this->values) && !empty($this->values['merchantNo']);
    }

    //  TODO    业务类型编号 -- （采用接口名称interface后的字符）如注册：toRegister

    public function SetServerCode($value)
    {
        $this->values['serverCode'] = $value;
    }

    public function GetServerCode()
    {
        return $this->values['serverCode'];
    }

    public function IsServerCodeSet()
    {
        return array_key_exists('serverCode', $this->values) && !empty($this->values['serverCode']);
    }

    //  TODO    接口版本号 -- 默认为：1.0.0

    public function SetVersion($value)
    {
        $this->values['version'] = $value;
    }

    public function GetVersion()
    {
        return $this->values['version'];
    }

    public function IsVersionSet()
    {
        return array_key_exists('version', $this->values) && !empty($this->values['version']);
    }

    //  TODO    时间戳

    public function SetDate()
    {
        $this->values['date'] = $_SERVER['REQUEST_TIME'];
    }

    public function GetDate()
    {
        return $this->values['date'];
    }

    public function IsDateSet()
    {
        return array_key_exists('date', $this->values) && !empty($this->values['date']);
    }


    //  TODO    签名，由merOrderId + merchantNo+date+params根据私钥生成如果有参数为null，签名串中应当做空字符串("")来处理

    /**
     * 设置签名，详见签名生成算法
     * @param string $value
     **/
    public function SetSign($sign)
    {
        $this->values['sign'] = $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return 值
     **/
    public function GetSign()
    {
        return $this->values['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return true 或 false
     **/
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values) && !empty($this->values['sign']);
    }

    /**
     * 输出Json数据
     * @return bool|string
     */
    public function toJson()
    {
        try {
            if (!is_array($this->params) || count($this->params) <= 0) {
                throw new \Trhui\TpamException('数组数据异常！');
            }
            return json_encode($this->params);
        } catch (\Trhui\TpamException $e) {
            $this->addError('toJson', $e->getMessage());
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
