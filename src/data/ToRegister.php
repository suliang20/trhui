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
 * 注册参数
 * TODO 请求地址：/interface/toRegister
 * Class ToRegister
 * @package trhui\data
 */
class ToRegister extends DataBase
{
    public function __construct()
    {
        $this->serverInterface = '/interface/toRegister';
        $this->serverCode = 'toRegister';
    }

    /**
     * 商户平台会员ID
     * 由商户平台定义
     * @param $value
     */
    public function SetMerUserId($value)
    {
        $this->params['merUserId'] = $value;
    }

    public function GetMerUserId()
    {
        return $this->params['merUserId'];
    }

    public function IsMerUserIdSet()
    {
        try {
            if (!(array_key_exists('merUserId', $this->params) && !empty($this->params['merUserId']))) {
                throw new TpamException('商户平台会员ID未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 账户类型
     * 空或0表示个人账户;1表示企业账户
     * @param $value
     */
    public function SetUserType($value)
    {
        $this->params['userType'] = $value;
    }

    public function GetUserType()
    {
        return $this->params['userType'];
    }

    /**
     * 手机号
     * @param $value
     */
    public function SetMobile($value)
    {
        $this->params['mobile'] = $value;
    }

    public function GetMobile()
    {
        return $this->params['mobile'];
    }

    public function IsMobileSet()
    {
        try {
            if (!(array_key_exists('mobile', $this->params) && !empty($this->params['mobile']))) {
                throw new TpamException('手机号未设置');
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