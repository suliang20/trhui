<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;


class ToRegister extends DataBase
{
    protected $params = array();

    //  TODO    商户平台会员ID -- 由商户平台定义

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
        return array_key_exists('merUserId', $this->params) && !empty($this->params['merUserId']);
    }

    //  TODO  账户类型 --   空或0表示个人账户;1表示企业账户

    public function SetUserType($value)
    {
        $this->params['userType'] = $value;
    }

    public function GetUserType()
    {
        return $this->params['userType'];
    }

    //  TODO    手机号

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
        return array_key_exists('mobile', $this->params) && !empty($this->params['mobile']);
    }


    //  TODO    前台回调地址

    public function SetFrontUrl($value)
    {
        $this->params['frontUrl'] = $value;
    }

    public function GetFrontUrl()
    {
        return $this->params['frontUrl'];
    }

    //  TODO    后台回调地址

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
        return array_key_exists('notifyUrl', $this->params) && !empty($this->params['notifyUrl']);
    }

}