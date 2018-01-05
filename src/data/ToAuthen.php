<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;


class ToAuthen extends DataBase
{

    public function __construct()
    {
        $this->serverInterface =  '/interface/toAuthen';
        $this->serverCode=  'toAuthen';
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