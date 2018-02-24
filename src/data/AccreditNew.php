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
 * 授权接口
 * TODO 请求地址：/tpam/service/interface/accreditNew
 * 业务系统发起请求时，将会跳转到授权页面，会员录入授权信息后将返回到业务系统，授权类别可分为授权冻结、授权支付、授权结算。会员授权业务系统后，在操作相应业务时，将不会出现密码确认窗口即可完成业务操作。
 * Class Accredit
 * @package trhui\data
 */
class AccreditNew extends DataBase
{
    const ACCREDIT_TYPE_FREEZE = 1;
    const ACCREDIT_TYPE_TRANSFER = 2;
    const ACCREDIT_TYPE_WITHDRAW = 3;

    public static $ACCREDIT_TYPE = [
        self::ACCREDIT_TYPE_FREEZE => '冻结',
        self::ACCREDIT_TYPE_TRANSFER => '转帐',
        self::ACCREDIT_TYPE_WITHDRAW => '提现',
    ];

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_ACCREDIT_NEW];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_ACCREDIT_NEW];
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
     * 授权类型
     * 1：冻结
     * 2：转账
     * 3：提现
     * @param $value
     */
    public function SetAccreditType($value)
    {
        $this->params['accreditType'] = $value;
    }

    public function GetAccreditType()
    {
        return $this->params['accreditType'];
    }

    public function IsAccreditTypeSet()
    {
        try {
            if (!(array_key_exists('accreditType', $this->params) && isset($this->params['accreditType']))) {
                throw new TpamException('授权类型未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 授权截止时间
     * 格式为yyyyMMdd，截止到当天零点
     * @param $value
     */
    public function SetLastDate($value)
    {
        $this->params['lastDate'] = $value;
    }

    public function GetLastDate()
    {
        return $this->params['lastDate'];
    }

    public function IsLastDateSet()
    {
        try {
            if (!(array_key_exists('lastDate', $this->params) && isset($this->params['lastDate']))) {
                throw new TpamException('授权截止时间未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 单笔授权限额
     * 以分为单位
     * 注：0为不限制
     * @param $value
     */
    public function SetAccreditAmountSingle($value)
    {
        $this->params['accreditAmountSingle'] = $value;
    }

    public function GetAccreditAmountSingle()
    {
        return $this->params['accreditAmountSingle'];
    }

    public function IsAccreditAmountSingleSet()
    {
        try {
            if (!(array_key_exists('accreditAmountSingle', $this->params) && isset($this->params['accreditAmountSingle']))) {
                throw new TpamException('单笔授权限额未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 总授权金额
     * 以分为单位
     * 注：累计授权金额不得大于总授权金额
     * @param $value
     */
    public function SetAccreditAmount($value)
    {
        $this->params['accreditAmount'] = $value;
    }

    public function GetAccreditAmount()
    {
        return $this->params['accreditAmount'];
    }

    public function IsAccreditAmountSet()
    {
        try {
            if (!(array_key_exists('accreditAmount', $this->params) && isset($this->params['accreditAmount']))) {
                throw new TpamException('总授权金额未设置');
            }
            if ($this->params['accreditAmount'] <= 0) {
                throw new TpamException('总授权金额不能小于等于0');
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