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
 * 支付审核接口
 * 请求地址：/tpam/service/interface/orderTransferAudit
 * 仅为延时支付使用
 * 未自动自付时才能进行审核“退回”，如已经超过自动支付时间，请调用支付接口
 * 当退款的业务系统订单号中包含多个商品时：以下整单“原商户订单号”为一单
 * 部分退款需传入交易金额(小于等于原交易金额)，退款多少商户服务费(小于等于原商户服务费)，费用不传则默认为整单退款。
 * 注：本接口已分开手续费和支付费用，如果商户通过接口退款时出现退款金额少于原支付金额，当交易自动确认时间到时会自动支付对接系统自行处理好账务数据。
 * Class OrderTransferAudit
 * @package trhui\data
 */
class Query extends DataBase
{
    /**
     * 审核类型
     * 1：通过
     * 2：退回
     */
    const ACTION_SETTLEMENT = 2;
    const ACTION_PAYMENT = 3;
    const ACTION_PAYMENT_AUDIT = 4;
    const ACTION_TRANSFER_AUDIT = 5;
    const ACTION_FREEZE = 6;

    public static $ACTION = [
        self::ACTION_SETTLEMENT => '结算',
        self::ACTION_PAYMENT => '支付',
        self::ACTION_PAYMENT_AUDIT => '支付审核',
        self::ACTION_TRANSFER_AUDIT => '转帐审核',
        self::ACTION_FREEZE => '冻结',
    ];

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_QUERY];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_QUERY];
    }

    /**
     * 原业务系统订单号
     * 需要查询的订单号，一次只能查一条
     * @param $value
     */
    public function SetOriginalMerOrderId($value)
    {
        $this->params['originalMerOrderId'] = $value;
    }

    public function GetOriginalMerOrderId()
    {
        return $this->params['originalMerOrderId'];
    }

    public function IsOriginalMerOrderIdSet()
    {
        try {
            if (!(array_key_exists('originalMerOrderId', $this->params) && !empty($this->params['originalMerOrderId']))) {
                throw new TpamException('原业务系统订单号不能为空');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 原业务系统交易订单号（交易金额非空时，不可为空）
     * 精确到单笔订单，为空时，整个支付单审核
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
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 查询类型
     * 2：结算
     * 3：支付
     * 4:支付审核
     * 5：转账审核
     * 6：冻结（仅查冻结单据，不查解冻单据）
     * @param $value
     */
    public function SetAction($value)
    {
        $this->params['action'] = $value;
    }

    public function GetAction()
    {
        return $this->params['action'];
    }

    public function IsActionSet()
    {
        try {
            if (!(array_key_exists('action', $this->params) && isset($this->params['action']))) {
                throw new TpamException('审核类型未设置');
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
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}