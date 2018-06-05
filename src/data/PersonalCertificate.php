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
 * 实名认证参数
 * TODO 请求地址：/tpam/service/interface/personalCertificate
 * 会员在提现操作之前，都必须要实名认证(绑定银行卡)
 * Class ToAuthen
 * @package trhui\data
 */
class PersonalCertificate extends DataBase
{

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_PERSONAL_CERTIFICATE];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_PERSONAL_CERTIFICATE];
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
     * 真实姓名
     * @param $value
     */
    public function SetRealName($value)
    {
        $this->params['realName'] = $value;
    }

    public function GetRealName()
    {
        return $this->params['realName'];
    }

    public function IsRealNameSet()
    {
        try {
            if (!(array_key_exists('realName', $this->params) && !empty($this->params['realName']))) {
                throw new TpamException('真实姓名未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 身份证号码
     * @param $value
     */
    public function SetCardNo($value)
    {
        $this->params['cardNo'] = $value;
    }

    public function GetCardNo()
    {
        return $this->params['cardNo'];
    }

    public function IsCardNoSet()
    {
        try {
            if (!(array_key_exists('cardNo', $this->params) && !empty($this->params['cardNo']))) {
                throw new TpamException('身份证号码未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 银行卡号
     * @param $value
     */
    public function SetBankCard($value)
    {
        $this->params['bankCard'] = $value;
    }

    public function GetBankCard()
    {
        return $this->params['bankCard'];
    }

    public function IsBankCardSet()
    {
        try {
            if (!(array_key_exists('bankCard', $this->params) && !empty($this->params['bankCard']))) {
                throw new TpamException('银行卡号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 手机号码
     * @param $value
     */
    public function SetMobile($value)
    {
        $this->params['mobile'] = $value;
    }

    public function GetMobile()
    {
        return $this->params['mobile'];
    }

    public function IsMobileSet()
    {
        try {
            if (!(array_key_exists('mobile', $this->params) && !empty($this->params['mobile']))) {
                throw new TpamException('手机号码未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 认证类型
     * 0:内地居民
     * 1:港澳台及外籍居民
     * @param $value
     */
    public function SetCertificationType($value)
    {
        $this->params['certificationType'] = $value;
    }

    public function GetCertificationType()
    {
        return $this->params['certificationType'];
    }

    public function IsCertificationTypeSet()
    {
        try {
            if (!(array_key_exists('certificationType', $this->params) && isset($this->params['certificationType']))) {
                throw new TpamException('认证类型未设置');
            }
            if (!in_array($this->params['certificationType'], [0, 1])) {
                throw new TpamException('认证类型错误');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 身份证正面
     * @param $value
     */
    public function SetCardFrontUrl($value)
    {
        $this->params['cardFrontUrl'] = $value;
    }

    public function GetCardFrontUrl()
    {
        return $this->params['cardFrontUrl'];
    }

    public function IsCardFrontUrlSet()
    {
        try {
            if (!(array_key_exists('cardFrontUrl', $this->params) && !empty($this->params['cardFrontUrl']))) {
                throw new TpamException('身份证正面未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 身份证反面
     * @param $value
     */
    public function SetCardBackUrl($value)
    {
        $this->params['cardBackUrl'] = $value;
    }

    public function GetCardBackUrl()
    {
        return $this->params['cardBackUrl'];
    }

    public function IsCardBackUrlSet()
    {
        try {
            if (!(array_key_exists('cardBackUrl', $this->params) && !empty($this->params['cardBackUrl']))) {
                throw new TpamException('身份证反面未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 机构证件照片
     * 个体工商户时需要传
     * @param $value
     */
    public function SetOrganDocumentsUrl($value)
    {
        $this->params['organDocumentsUrl'] = $value;
    }

    public function GetOrganDocumentsUrl()
    {
        return $this->params['organDocumentsUrl'];
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