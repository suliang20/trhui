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
 * 会员自助登录
 * 后台接口
 * 商户会员登录
 * TODO 请求地址：/club/service/interface/memberLogin
 * Class MemberLogin
 * @package trhui\data
 */
class MemberLogin extends DataBase
{
    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_MEMBER_LOGIN];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_MEMBER_LOGIN];
    }

    /**
     * 商户平台会员ID
     * 由商户平台定义
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
            if (!(array_key_exists('userId', $this->params) && !empty($this->params['userId']))) {
                throw new TpamException('清算通系统会员ID未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

}