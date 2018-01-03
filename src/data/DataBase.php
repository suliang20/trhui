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
    protected $values = array();


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

}
