<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;


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
        $this->serverInterface = '/interface/accredit';
        $this->serverCode = 'accredit';
    }

    //  TODO    清算通系统会员ID

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
        return array_key_exists('userId', $this->params) && !empty($this->params['userId']);
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

    public function IsFrontUrlSet()
    {
        return array_key_exists('frontUrl', $this->params) && !empty($this->params['frontUrl']);
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