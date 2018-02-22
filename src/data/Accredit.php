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
 * 授权参数
 * TODO 请求地址：/interface/accredit
 * 商户平台发起请求时，将会跳转到授权页面，会员录入授权信息后将返回到商户平台，授权类别可分为授权冻结、授权转账、授权提现。会员授权商户平台后，在操作相应业务时，将不会出现密码确认窗口即可完成业务操作。
 * Class Accredit
 * @package trhui\data
 */
class Accredit extends DataBase
{

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_ACCREDIT];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_ACCREDIT];
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