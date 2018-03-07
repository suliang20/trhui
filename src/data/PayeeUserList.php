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
 * 收款人列表
 * 注：开发时,收款人列表长度控制为最多20对象
 * 业务子参数：
 * Class PayeeUserList
 * @package trhui\data
 */
class PayeeUserList extends DataBase
{
    /**
     * 转账方式
     * 0：托管转账，需要商户管理员审核通过后，资金才会划转到对方账户；
     * 1：直接转账，转账成功后，资金直接划转到对方账户中。
     */
    const TRANSFER_TYPE_COSTODY = 0;        //  托管转账
    const TRANSFER_TYPE_DIRECT = 1;         //  直接转账
    public static $TRANSFER_TYPE = [
        self::TRANSFER_TYPE_COSTODY => '托管转账',
        self::TRANSFER_TYPE_DIRECT => '直接转账',
    ];

    /**
     * 佣金收取方式
     * 0：立即收取；
     * 1：交易结束时收取。
     * 如果商户有收取交易手续费，且转账方式为“托管转账”时有效
     */
    const FEE_TYPE_PROMPTLY = 0;                //  立即收取
    const FEE_TYPE_TRANSACTION_CLOSE = 1;     //  交易结束
    public static $FEE_TYPE = [
        self::FEE_TYPE_PROMPTLY => '立即收取',
        self::FEE_TYPE_TRANSACTION_CLOSE => '交易结束',
    ];


    /**
     * 商户平台交易订单号
     * @param $value
     */
    public function SetOrderId($value)
    {
        $this->params['orderId'] = $value;
    }

    public function GetOrderId()
    {
        return $this->params['orderId'];
    }

    public function IsOrderIdSet()
    {
        try {
            if (!(array_key_exists('orderId', $this->params) && !empty($this->params['orderId']))) {
                throw new TpamException('商户平台交易订单号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 收款方商户平台账号
     * @param $value
     */
    public function SetPayeeUserId($value)
    {
        $this->params['payeeUserId'] = $value;
    }

    public function GetPayeeUserId()
    {
        return $this->params['payeeUserId'];
    }

    public function IsPayeeUserIdSet()
    {
        try {
            if (!(array_key_exists('payeeUserId', $this->params) && isset($this->params['payeeUserId']))) {
                throw new TpamException('收款方商户平台账号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 收款金额,单位：分
     * 金额必须大于0分,且和feeToMerchant累加起来等于付款金额，否则视为异常
     * @param $value
     */
    public function SetPayeeAmount($value)
    {
        $this->params['payeeAmount'] = $value;
    }

    public function GetPayeeAmount()
    {
        return $this->params['payeeAmount'];
    }

    public function IsPayeeAmountSet()
    {
        try {
            if (!(array_key_exists('payeeAmount', $this->params) && !empty($this->params['payeeAmount']))) {
                throw new TpamException('收款金额未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 商户平台收取佣金,单位：分
     * 可为0分,且和payeeAmount累加起来等于付款金额，否则视为异常
     * @param $value
     */
    public function SetFeeToMerchant($value)
    {
        $this->params['feeToMerchant'] = $value;
    }

    public function GetFeeToMerchant()
    {
        return $this->params['feeToMerchant'];
    }

    public function IsFeeToMerchantSet()
    {
        try {
            if (!(array_key_exists('feeToMerchant', $this->params) && isset($this->params['feeToMerchant']))) {
                throw new TpamException('商户平台收取佣金未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 转账方式
     * 0：托管转账，需要商户管理员审核通过后，资金才会划转到对方账户；1：直接转账，转账成功后，资金直接划转到对方账户中。
     * @param $value
     */
    public function SetTransferType($value)
    {
        $this->params['transferType'] = $value;
    }

    public function GetTransferType()
    {
        return $this->params['transferType'];
    }

    public function IsTransferTypeSet()
    {
        try {
            if (!(array_key_exists('transferType', $this->params) && isset($this->params['transferType']))) {
                throw new TpamException('转账方式未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 佣金收取方式
     * 0：立即收取；1：交易结束时收取。如果商户有收取交易手续费，且转账方式为“托管转账”时有效
     * @param $value
     */
    public function SetFeeType($value)
    {
        $this->params['feeType'] = $value;
    }

    public function GetFeeType()
    {
        return $this->params['feeType'];
    }

    public function IsFeeTypeSet()
    {
        try {
            if (!(array_key_exists('feeType', $this->params) && isset($this->params['feeType']))) {
                throw new TpamException('佣金收取方式未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 自动支付时间：天
     * 当transferType为“0：托管转账”时有效,当传入0或空时系统不会自动转账确认
     * @param $value
     */
    public function SetAutoPayday($value)
    {
        $this->params['autoPayday'] = $value;
    }

    public function GetAutoPayday()
    {
        return $this->params['autoPayday'];
    }

    public function IsAutoPaydaySet()
    {
        return true;
        try {
            if (!(array_key_exists('autoPayday', $this->params) && !empty($this->params['autoPayday']))) {
                throw new TpamException('自动支付时间未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 商品列表json格式
     * @param $value
     */
    public function SetItemList($value)
    {
        $this->params['itemList'] = $value;
    }

    public function GetItemList()
    {
        return $this->params['itemList'];
    }

    public function IsItemListSet()
    {
        return true;
        try {
            if (!(array_key_exists('itemList', $this->params) && !empty($this->params['itemList']))) {
                throw new TpamException('商品列表未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}