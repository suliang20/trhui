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
 * 会员资金查询
 * 后台接口
 * TODO 请求地址：/tpam/service/interface/getBalance
 * Class GetBalance
 * @package trhui\data
 */
class GetBalance extends DataBase
{
    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_GET_BALANCE];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_GET_BALANCE];
    }

    /**
     * 清算通系统会员ID
     * 如果为空或为0，则查询业务系统资金
     * 否则查询会员资金
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
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}