<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:18
 */

namespace trhui\data;

use trhui\TpamException;

/**
 * 参数基类
 * Class DataBase
 * @package trhui\data
 */
class DataBase extends Data
{

    const SERVER_ORDER_TRANSFER = 'orderTransfer';
    const SERVER_ORDER_TRANSFER_AUDIT = 'orderTransferAudit';
    const SERVER_ACCREDIT = 'accredit';
    const SERVER_DELAY_AUTO_PAYDAY = 'delayAutoPayday';
    const SERVER_REFUND = 'refund';
    const SERVER_TO_AUTHEN = 'toAuthen';
    const SERVER_TO_REGISTER = 'toRegister';
    const SERVER_QUERY = 'query';
    const SERVER_MODIFY_PHONE = 'modifyPhone';
    const SERVER_MEMBER_LOGIN = 'memberLogin';
    const SERVER_ACCREDIT_NEW = 'accreditNew';

    public static $SERVER = [
        self::SERVER_ORDER_TRANSFER => [
            'interface' => '/tpam/service/interface/orderTransfer',
            'code' => 'orderTransfer',
        ],
        self::SERVER_ORDER_TRANSFER_AUDIT => [
            'interface' => '/tpam/service/interface/orderTransferAudit',
            'code' => 'orderTransferAudit',
        ],
        self::SERVER_ACCREDIT => [
            'interface' => '/tpam/service/interface/accredit',
            'code' => 'accredit',
        ],
    ];

    public static $SERVER_INTERFACE = [
        self::SERVER_ORDER_TRANSFER => '/tpam/service/interface/orderTransfer',
        self::SERVER_ORDER_TRANSFER_AUDIT => '/tpam/service/interface/orderTransferAudit',
        self::SERVER_ACCREDIT => '/tpam/service/interface/accredit',
        self::SERVER_DELAY_AUTO_PAYDAY => '/tpam/service/interface/delayAutoPayday',
        self::SERVER_REFUND => '/tpam/service/interface/refund',
        self::SERVER_TO_AUTHEN => '/tpam/service/interface/toAuthen',
        self::SERVER_TO_REGISTER => '/tpam/service/interface/toRegister',
        self::SERVER_QUERY => '/tpam/service/interface/query',
        self::SERVER_MODIFY_PHONE => '/tpam/service/interface/modifyPhone',
        self::SERVER_MEMBER_LOGIN => '/club/service/interface/memberLogin',
        self::SERVER_ACCREDIT_NEW => '/tpam/service/interface/accreditNew',
    ];

    public static $SERVER_CODE = [
        self::SERVER_ORDER_TRANSFER => 'orderTransfer',
        self::SERVER_ORDER_TRANSFER_AUDIT => 'orderTransferAudit',
        self::SERVER_ACCREDIT => 'accredit',
        self::SERVER_DELAY_AUTO_PAYDAY => 'delayAutoPayday',
        self::SERVER_REFUND => 'refund',
        self::SERVER_TO_AUTHEN => 'toAuthen',
        self::SERVER_TO_REGISTER => 'toRegister',
        self::SERVER_QUERY => 'query',
        self::SERVER_MODIFY_PHONE => 'modifyPhone',
        self::SERVER_MEMBER_LOGIN => 'memberLogin',
        self::SERVER_ACCREDIT_NEW => 'accreditNew',
    ];

    protected $params = array();

    /**
     * 服务接口
     * @var
     */
    protected $serverInterface;

    /**
     * 业务类型编码
     * @var
     */
    protected $serverCode;

    //  TODO    自定义参数1

    public function SetParameter1($value)
    {
        $this->params['parameter1'] = $value;
    }

    public function GetParameter1()
    {
        return $this->params['parameter1'];
    }

    //  TODO   扩展数据

    public function SetExtendData($value)
    {
        $this->params['extendData'] = $value;
    }

    public function GetExtendData()
    {
        return $this->params['extendData'];
    }

    /**
     * 获取服务接口
     * @return mixed
     */
    public function getServerInterface()
    {
        return $this->serverInterface;
    }

    /**
     * 获取业务类型编码
     * @return mixed
     */
    public function getServerCode()
    {
        return $this->serverCode;
    }

    /**
     * 输出Json数据
     * @return bool|string
     */
    public function toJson()
    {
        try {
            if (!$this->checkParams()) {
                throw new TpamException('参数异常！');
            }
            return json_encode($this->params);
        } catch (TpamException $e) {
            $this->addError('toJson', $e->getMessage());
        }
        return false;
    }

    /**
     * 获取参数值
     */
    public function getParams()
    {
        try {
            if (!$this->checkParams()) {
                throw new TpamException('参数异常！');
            }
            return $this->params;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 检查参数
     * @return bool
     */
    public function checkParams()
    {
        try {
            foreach (get_class_methods($this) as $method) {
                if (preg_match('/^Is\w*Set$/', $method)) {
                    if (!$this->$method()) {
                        throw new TpamException(substr($method, 2, -3) . '未设置！');
                    }
                }
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}
