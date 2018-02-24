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
 * 延长自动转账时间
 * TODO 请求地址：/interface/delayAutoPayday
 * 当转账方式为托管转账时，会员交易产生纠纷或托管时间快时，卖方还没履行完义务时，会员可申请延长支付时间。
 * PS:当天默认审核的单据无法延长审核时间
 * Class DelayAutoPayday
 * @package trhui\data
 */
class DelayAutoPayday extends DataBase
{
    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_DELAY_AUTO_PAYDAY];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_DELAY_AUTO_PAYDAY];
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
     * 清算通系统会员ID
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
     * 延期天数
     * 必须大于0，将在原订单自动支付的基础上+N
     * @param $value
     */
    public function SetDays($value)
    {
        $this->params['days'] = $value;
    }

    public function GetDays()
    {
        return $this->params['days'];
    }

    public function IsDaysSet()
    {
        try {
            if (!(array_key_exists('days', $this->params) && !empty($this->params['days']))) {
                throw new TpamException('延期天数未设置');
            }
            if ($this->params['days'] <= 0) {
                throw new TpamException('延期天数必须大于0');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }


    /**
     * 前台回调地址
     * @param $value
     */
    public function SetFrontUrl($value)
    {
        $this->params['frontUrl'] = $value;
    }

    public function GetFrontUrl()
    {
        return $this->params['frontUrl'];
    }

    public function IsFrontUrlSet()
    {
        try {
            if (!(array_key_exists('frontUrl', $this->params) && !empty($this->params['frontUrl']))) {
                throw new TpamException('前台回调地址未设置');
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