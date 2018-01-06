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
 * 支付参数
 * 注：测试环境仅能进行“个人网银支付”、“手机WAP支付”
 * 其中“个人网银支付”仅支持上海农商银行：账号123456789001，密码789001
 * “手机WAP支付”：
 * 账号贷记卡：6221558812340000
 * cvn2:123
 * 有效期：23年11月
 * 短信验证码：111111
 *
 * 案例:商户平台在买家向卖家购买商品并支付时，将调用该接口进行支付，商户平台调用该接口，清算通系统会跳转到密码确认页面。
 * 转账可选择是否用自有资金进行转账（会员在商户平台的余额）
 * A、    转账不使用余额，直接在线支付，支付的时候会跳转到对应的支付方式的支付页面进行支付；
 * B、    使用余额，余额足够直接支付页面，不足则无法转账
 * Class OrderTransfer
 * @package trhui\data
 */
class OrderTransfer extends DataBase
{

    public function __construct()
    {
        $this->serverInterface = '/interface/orderTransfer';
        $this->serverCode = 'orderTransfer';
    }

    /**
     * 交易金额，单位：分
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
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 付款方商户平台账号
     * @param $value
     */
    public function SetPayerUserId($value)
    {
        $this->params['payerUserId'] = $value;
    }

    public function GetPayerUserId()
    {
        return $this->params['payerUserId'];
    }

    public function IsPayerUserIdSet()
    {
        try {
            if (!(array_key_exists('payerUserId', $this->params) && !empty($this->params['payerUserId']))) {
                throw new TpamException('付款方商户平台账号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 业务类型
     * 1：消费转账；
     * @param $value
     */
    public function SetActionType($value)
    {
        $this->params['actionType'] = $value;
    }

    public function GetActionType()
    {
        return $this->params['actionType'];
    }

    public function IsActionTypeSet()
    {
        try {
            if (!(array_key_exists('actionType', $this->params) && !empty($this->params['actionType']))) {
                throw new TpamException('业务类型未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 支付方式
     * 0:余额支付，1:在线支付
     * @param $value
     */
    public function SetTransferPayType($value = 0)
    {
        $this->params['transferPayType'] = $value;
    }

    public function GetTransferPayType()
    {
        return $this->params['transferPayType'];
    }

    public function IsTransferPayTypeSet()
    {
        return true;
        try {
            if (!(array_key_exists('transferPayType', $this->params) && !empty($this->params['transferPayType']))) {
                throw new TpamException('支付方式未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 支付类型
     * 1：个人网银支付
     * 2：企业网银
     * 3：快捷支付
     * 4：手机WAP支付
     * 5：代收/代扣
     * 6：E-pos支付
     * 7：微信扫码支付
     * 8：支付宝扫码支付
     * 9：微信公众号支付
     * 10：支付宝服务窗支付
     * 11：A-pos支付
     * 12：微信控件支付
     * 13：微信B扫C支付
     * 14：支付宝B扫C支付
     * 15：微信H5支付
     * 16：微信小程序支付
     * 转账支付方式为1时有用
     * @param $value
     */
    public function SetTopupType($value)
    {
        $this->params['topupType'] = $value;
    }

    public function GetTopupType()
    {
        return $this->params['topupType'];
    }

    public function IsTopupTypeSet()
    {
        try {
            if (!(array_key_exists('topupType', $this->params) && !empty($this->params['topupType']))) {
                throw new TpamException('支付类型未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 卡种
     * 1：借记卡
     * 2：贷记卡
     * 支付类型为EPOS必填
     * @param $value
     */
    public function SetPayType($value = '')
    {
        if (!empty($value)) {
            $this->params['payType'] = $value;
        }
    }

    public function GetPayType()
    {
        return $this->params['payType'];
    }

    public function IsPayTypeSet()
    {
        return true;
        try {
            if (!(array_key_exists('payType', $this->params) && !empty($this->params['payType']))) {
                throw new TpamException('卡种未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 支付手续费承担方
     * 0：商户平台；
     * 1：收款方
     * Ps：非支付转账业务，此值无意义
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
        return true;
        try {
            if (!(array_key_exists('feePayer', $this->params) && !empty($this->params['feePayer']))) {
                throw new TpamException('支付手续费承担方未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 收款人信息列表
     * 收款人列表，详见下表（采用json格式）
     * 当手续费商户平台支付：20个对象
     * 手续费收款方支付时1个对象
     * @param $value
     */
    public function SetPayeeUserList(PayeeUserList $inputOjb)
    {
        try {
            if (!$payeeUserListParams = $inputOjb->getParams()) {
                $this->errors = array_merge($this->errors, $inputOjb->errors);
                throw new TpamException('获取收款人数据异常');
            }
            $this->params['payeeUserList'] = json_encode($payeeUserListParams);
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function GetPayeeUserList()
    {
        return $this->params['payeeUserList'];
    }

    public function IsPayeeUserListSet()
    {
        try {
            if (!(array_key_exists('payeeUserList', $this->params) && !empty($this->params['payeeUserList']))) {
                throw new TpamException('收款人列表数据不能为空');
            }
            return true;
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('IsPayeeUserListSet', $e->getMessage());
            }
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
            if (!(array_key_exists('frontUrl', $this->params) && !empty($this->params['frontUrl']))) {
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
            if (!(array_key_exists('notifyUrl', $this->params) && !empty($this->params['notifyUrl']))) {
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