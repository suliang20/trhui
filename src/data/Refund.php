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
 * 部分退款
 * 后台接口，具体退款状态以异步回调为准。
 * TODO 请求地址：/interface/refund
 * ps:仅支持托管转账未审核的部份退款，可多次退款
 * 支持直接转账当天全额退款
 * 退款是原路退回的
 *
 * 目前支持手机wap支付、微信扫码、支付宝扫码、公众号支付、支付宝服务窗支付、微信B扫C、支付宝B扫C的转账退款
 * Class Refund
 * @package trhui\data
 */
class Refund extends DataBase
{
    /**
     * 退款业务类型：
     * 1：交易退款（转账退款）
     */
    const REFUND_TYPE_TRANSACTION = 1;      //  退款业务类型
    const REFUND_TYPE = [
        '1' => '退款业务类型'
    ];

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_REFUND];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_REFUND];
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
     * 退款业务类型
     * 1：交易退款（转账退款）
     * @param $value
     */
    public function SetRefundType($value)
    {
        $this->params['refundType'] = $value;
    }

    public function GetRefundType()
    {
        return $this->params['refundType'];
    }

    public function IsRefundTypeSet()
    {
        try {
            if (!(array_key_exists('refundType', $this->params) && isset($this->params['refundType']))) {
                throw new TpamException('退款业务类型未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 退款金额
     * 金额，单位分
     * @param $value
     */
    public function SetAmount($value)
    {
        $this->params['amount'] = $value;
    }

    public function GetAmount()
    {
        return $this->params['amount'];
    }

    public function IsAmountSet()
    {
        try {
            if (!(array_key_exists('amount', $this->params) && !empty($this->params['amount']))) {
                throw new TpamException('退款金额未设置');
            }
            if ($this->params['amount'] <= 0) {
                throw new TpamException('退款金额必须大于0');
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
     * 原商户平台交易订单号
     * 原商户平台交易订单号，即需要延期的订单
     * @param $value
     */
    public function SetOriginalOrderId($value)
    {
        $this->params['originalOrderId'] = $value;
    }

    public function GetOriginalOrderId()
    {
        return $this->params['originalOrderId'];
    }

    public function IsOriginalOrderIdSet()
    {
        try {
            if (!(array_key_exists('originalOrderId', $this->params) && !empty($this->params['originalOrderId']))) {
                throw new TpamException('原商户平台交易订单号未设置');
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