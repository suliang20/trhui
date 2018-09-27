<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;

use trhui\TpamException;


/**
 * 全额退款
 * 后台接口
 * TODO 请求地址：/tpam/service/interface/refundAll
 * ps:支持延时到账支付全部退款和直接支付全部退款，对于整个支付订单中有产生退款的都不能调用该接口
 * Class RefundAll
 * @package trhui\data
 */
class RefundAll extends DataBase
{

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_REFUND_ALL];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_REFUND_ALL];
    }

    /**
     * 清算通系统会员ID
     * 付款人ID
     * @param $value
     */
    public function SetUserId($value)
    {
        $this->params['userId'] = $value;
    }

    public function GetUserId()
    {
        return $this->params['userId'];
    }

    public function IsUserIdSet()
    {
        try {
            if (!(array_key_exists('userId', $this->params) && isset($this->params['userId']))) {
                throw new TpamException('清算通系统会员ID未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 原系统转账响应订单号
     * 该订单号为商户平台调用转账接口时，清算通系统返回的订单号
     * @param $value
     */
    public function SetOriginalPlatformOrderId($value)
    {
        $this->params['originalPlatformOrderId'] = $value;
    }

    public function GetOriginalPlatformOrderId()
    {
        return $this->params['originalPlatformOrderId'];
    }

    public function IsOriginalPlatformOrderIdSet()
    {
        try {
            if (!(array_key_exists('originalPlatformOrderId', $this->params) && !empty($this->params['originalPlatformOrderId']))) {
                throw new TpamException('原系统转账响应订单号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 原商户平台转账请求订单号
     * @param $value
     */
    public function SetOriginalMerOrderId($value)
    {
        $this->params['originalMerOrderId'] = $value;
    }

    public function GetOriginalMerOrderId()
    {
        return $this->params['originalMerOrderId'];
    }

    public function IsOriginalMerOrderIdSet()
    {
        try {
            if (!(array_key_exists('originalMerOrderId', $this->params) && !empty($this->params['originalMerOrderId']))) {
                throw new TpamException('原商户平台转账请求订单号');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 后台回调地址
     * @param $value
     */
    public function SetNotifyUrl($value)
    {
        $this->params['notifyUrl'] = $value;
    }

    public function GetNotifyUrl()
    {
        return $this->params['notifyUrl'];
    }

    public function IsNotifyUrlSet()
    {
        try {
            if (!(array_key_exists('notifyUrl', $this->params) && !empty($this->params['notifyUrl']))) {
                throw new TpamException('后台回调地址未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}