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
class OrderTransferAudit extends DataBase
{
    /**
     * 审核类型
     * 1：通过
     * 2：退回
     */
    const AUDIT_TYPE_PASS = 1;
    const AUDIT_TYPE_BACK = 2;
    const AUDTI_TYPE = [
        '1' => '通过',
        '2' => '退回',
    ];

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_ORDER_TRANSFER_AUDIT];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_ORDER_TRANSFER_AUDIT];
    }

    /**
     * 系统支付响应订单号
     * 仅传OriginalPlatformOrder则整批审核,金额部分会根据对应订单剩余为审核金额进行审核
     * @param $value
     */
    public function SetOriginalPlatformOrder($value)
    {
        $this->params['originalPlatformOrder'] = $value;
    }

    public function GetOriginalPlatformOrder()
    {
        return $this->params['originalPlatformOrder'];
    }

    public function IsOriginalPlatformOrderSet()
    {
        try {
            if (!(array_key_exists('originalPlatformOrder', $this->params) && !empty($this->params['originalPlatformOrder']))) {
                throw new TpamException('系统支付响应订单号未设置');
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
            if (!(array_key_exists('originalOrderId', $this->params) && !empty($this->params['originalOrderId']))) {
                throw new TpamException('原业务系统交易订单号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 总交易金额金额,单位：分
     * payAmount+ fee
     * @param $value
     */
    public function SetAmount($value)
    {
        $this->params['amount'] = $value;
    }

    public function GetAmount()
    {
        return $this->params['amount'];
    }

    public function IsAmountSet()
    {
        try {
            if (isset($this->params['amount']) && $this->params['amount'] <= 0) {
                $amount = $this->params['amount'];
                if ($amount <= 0) {
                    throw new TpamException('总交易金额必须大于0');
                }
                $payAmount = !empty($this->params['payAmount']) ? $this->params['payAmount'] : 0;
                $fee = !empty($this->params['fee']) ? $this->params['fee'] : 0;
                if ($amount != ($payAmount + $fee)) {
                    throw new TpamException('总交易金额等于交易金额加手续费');
                }
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 会员通过(子商户回退)金额金额,单位：分
     * 必须大于0分且小于累计原收款金额 (主要为处理同时包含多个业务系统交易订单且处理其中一条时使用，处理多条时此字段无意义)
     * @param $value
     */
    public function SetPayAmount($value)
    {
        $this->params['payAmount'] = $value;
    }

    public function GetPayAmount()
    {
        return $this->params['payAmount'];
    }

    public function IsPayAmountSet()
    {
        try {
            if (isset($this->params['payAmount']) && $this->params['payAmount'] <= 0) {
                throw new TpamException('交易金额必须大于0');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 通过(回退)交易手续费,单位：分
     * 可为0分、且累计金额小于总手续费金额(主要为处理同时包含多个业务系统交易订单且处理其中一条时使用，处理多条时此字段无意义)、通过时需要原交易手续费为交易成功收取才有效
     * @param $value
     */
    public function SetFee($value)
    {
        $this->params['fee'] = $value;
    }

    public function GetFee()
    {
        return $this->params['fee'];
    }

    public function IsFeeSet()
    {
        try {
            if (isset($this->params['fee']) && $this->params['fee'] <= 0) {
                throw new TpamException('手续费必须大于等于0');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 审核类型
     * 1：通过；2：退回
     * @param $value
     */
    public function SetAuditType($value)
    {
        $this->params['auditType'] = $value;
    }

    public function GetAuditType()
    {
        return $this->params['auditType'];
    }

    public function IsAuditTypeSet()
    {
        try {
            if (!(array_key_exists('auditType', $this->params) && isset($this->params['auditType']))) {
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