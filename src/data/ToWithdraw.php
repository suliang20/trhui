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
 * 结算接口
 * TODO 请求地址：/tpam/service/interface/toWithdraw
 * 注：结算前需要先银行账户认证
 * 结算到账的金额为请求的结算金额，费用为额外收取的。系统将按照合同约定计算手续费，从参数中指定费用承担方扣取相应手续费，同时，如果商户需要另外向会员收取手续费的，可以feeToMerchant字段中指定。
 * Class ToWithdraw
 * @package trhui\data
 */
class ToWithdraw extends DataBase
{

    /**
     * 手续费承担方
     * 0：业务系统；
     * Ps：非支付转账业务，此值无意义
     */
    const FEE_PAYER_OPERATION_SYSTEM = 0;                    // 业务系统
    const FEE_PAYER = [
        '0' => '业务系统',
    ];

    /**
     * 是否需要审核
     * 1：是
     * 0：否
     */
    const IS_NEED_FOR_AUDIT_NO = 0;
    const IS_NEED_FOR_AUDIT_YES = 1;
    const IS_NEED_FOR_AUDIT = [
        self::IS_NEED_FOR_AUDIT_NO => '否',
        self::IS_NEED_FOR_AUDIT_YES => '是',
    ];


    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_TO_WITHDRAW];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_TO_WITHDRAW];
    }

    /**
     * 清算通系统会员ID
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

    /**
     * 结算金额，单位：分
     * 以“分”为单位，不能带小数点。如1.12元，传的时候乘以100，值为：112；
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
            if (!(array_key_exists('amount', $this->params) && !empty($this->params['amount']))) {
                throw new TpamException('交易金额未设置');
            }
            if ($this->params['amount'] < 1) {
                throw new TpamException('提现金额必须大于等于1分');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 手续费承担方
     * 0：业务系统；仅支持平台方承担
     * @param $value
     */
    public function SetFeePayer($value = 0)
    {
        $this->params['feePayer'] = $value;
    }

    public function GetFeePayer()
    {
        return $this->params['feePayer'];
    }

    public function IsFeePayerSet()
    {
        try {
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
            if (!empty($this->params['feeToMerchant']) && floor($this->params['feeToMerchant']) != $this->params['feeToMerchant']) {
                throw new TpamException('商户平台收取佣金非整数');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 是否需要审核
     * 1：是
     * 0：否
     * @param $value
     */
    public function SetIsNeedForAudit($value)
    {
        $this->params['isNeedForAudit'] = $value;
    }

    public function GetIsNeedForAudit()
    {
        return $this->params['isNeedForAudit'];
    }

    public function IsIsNeedForAuditSet()
    {
        try {
            if (!empty($this->params['isNeedForAudit']) && floor($this->params['isNeedForAudit']) != $this->params['isNeedForAudit']) {
                throw new TpamException('是否需要审核参数非整数');
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
            if (!(array_key_exists('frontUrl', $this->params) && isset($this->params['frontUrl']))) {
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
            if (!(array_key_exists('notifyUrl', $this->params) && isset($this->params['notifyUrl']))) {
                throw new TpamException('后台回调地址未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 自定义参数1
     * 如果支付方式选择“9：微信公众号支付或者16：微信小程序支付”这里需要输入微信商户微信公众账号ID、用户ID，格式如下：{ "subAppid ": "微信公众账号ID "," userId ": "微信用户openid"}
     * 如果支付方式选择“10：支付宝服务窗支付”这里需要输入用户ID，格式如下：{"userId":"支付宝服务窗用户id"}
     * 如果支付方式选择“12：微信控件支付”这里需要输入APPID（在微信开放平台上申请的APPID）
     * 如果支付方式选择“13：微信B扫C支付或14：支付宝B扫C支付”这里需要输入扫码支付授权码
     * 如果支付方式选择“15：微信H5支付”这里需要输入：
     *      WAP网站应用 {“h5_info”: {“type”:“Wap”,“wap_url”: “WAP网站URL地址”,“wap_name”: “WAP网站名”}}
     *      IOS移动应用 {“h5_info”: {“type”:“IOS”,“app_name”: “应用名”,“bundle_id”: “包名”}}
     *      安卓移动应用 {“h5_info”: {“type”:“Android”,“app_name”: “应用名”,“package_name”: “包名”}}
     * @param $value
     */
    public function SetParameter1($value)
    {
        $this->params['parameter1'] = $value;
    }

    public function GetParameter1()
    {
        return $this->params['parameter1'];
    }

    public function IsParameter1Set()
    {
        return true;
        return array_key_exists('parameter1', $this->params) && !empty($this->params['parameter1']);
    }

    /**
     * 自定义参数2
     * 渠道：招商
     * 9：公众号支付
     * 如果没有传参数，支付成功后关闭支付页面；
     * 如果有传URL，支付成功后回调至商户前台回调地址
     *
     * 12：控件支付
     * 有传值显示商品名称，没传值显示商户名称
     * @param $value
     */
    public function SetParameter2($value)
    {
        $this->params['parameter2'] = $value;
    }

    public function GetParameter2()
    {
        return $this->params['parameter2'];
    }

    public function IsParameter2Set()
    {
        return true;
        return array_key_exists('parameter2', $this->params) && !empty($this->params['parameter2']);
    }
}