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
 * 企业银行帐户认证参数
 * TODO 请求地址：/tpam/service/interface/enterpriseCertificate
 * 会员在提现操作之前，都必须要实名认证(绑定银行卡)
 * Class ToAuthen
 * @package trhui\data
 */
class EnterpriseCertificate extends DataBase
{

    public function __construct()
    {
        $this->serverInterface = self::$SERVER_INTERFACE[self::SERVER_ENTERPRISE_CERTIFICATE];
        $this->serverCode = self::$SERVER_CODE[self::SERVER_ENTERPRISE_CERTIFICATE];
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
     * 组织机构代码
     * @param $value
     */
    public function SetOrganCode($value)
    {
        $this->params['organCode'] = $value;
    }

    public function GetOrganCode()
    {
        return $this->params['organCode'];
    }

    public function IsOrganCodeSet()
    {
        try {
            if (!(array_key_exists('organCode', $this->params) && !empty($this->params['organCode']))) {
                throw new TpamException('组织机构代码未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 组织机构全称
     * @param $value
     */
    public function SetBusinessLicense($value)
    {
        $this->params['businessLicense'] = $value;
    }

    public function GetBusinessLicense()
    {
        return $this->params['businessLicense'];
    }

    public function IsBusinessLicenseSet()
    {
        try {
            if (!(array_key_exists('businessLicense', $this->params) && !empty($this->params['businessLicense']))) {
                throw new TpamException('组织机构全称');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 法人姓名
     * @param $value
     */
    public function SetLegalPersonName($value)
    {
        $this->params['legalPersonName'] = $value;
    }

    public function GetLegalPersonName()
    {
        return $this->params['legalPersonName'];
    }

    public function IsLegalPersonNameSet()
    {
        try {
            if (!(array_key_exists('legalPersonName', $this->params) && !empty($this->params['legalPersonName']))) {
                throw new TpamException('法人姓名未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 法人证件号
     * @param $value
     */
    public function SetCorporateId($value)
    {
        $this->params['corporateId'] = $value;
    }

    public function GetCorporateId()
    {
        return $this->params['corporateId'];
    }

    public function IsCorporateIdSet()
    {
        try {
            if (!(array_key_exists('corporateId', $this->params) && !empty($this->params['corporateId']))) {
                throw new TpamException('法人证件号未设置');
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
     * 对公帐号
     * @param $value
     */
    public function SetAcctNo($value)
    {
        $this->params['acctNo'] = $value;
    }

    public function GetAcctNo()
    {
        return $this->params['acctNo'];
    }

    public function IsAcctNoSet()
    {
        try {
            if (!(array_key_exists('acctNo', $this->params) && !empty($this->params['acctNo']))) {
                throw new TpamException('对公帐号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 对公户名
     * @param $value
     */
    public function SetAcctName($value)
    {
        $this->params['acctName'] = $value;
    }

    public function GetAcctName()
    {
        return $this->params['acctName'];
    }

    public function IsAcctNameSet()
    {
        try {
            if (!(array_key_exists('acctName', $this->params) && !empty($this->params['acctName']))) {
                throw new TpamException('对公户名未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 开户网点联行号
     * @param $value
     */
    public function SetBranchNo($value)
    {
        $this->params['branchNo'] = $value;
    }

    public function GetBranchNo()
    {
        return $this->params['branchNo'];
    }

    public function IsBranchNoSet()
    {
        try {
            if (!(array_key_exists('branchNo', $this->params) && !empty($this->params['branchNo']))) {
                throw new TpamException('开户网点联行号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 机构类型
     * 0:营利性组织
     * 1:非营利性组织
     * @param $value
     */
    public function SetOrganType($value)
    {
        $this->params['organType'] = $value;
    }

    public function GetOrganType()
    {
        return $this->params['organType'];
    }

    public function IsOrganTypeSet()
    {
        try {
            if (!(array_key_exists('organType', $this->params) && isset($this->params['organCode']))) {
                throw new TpamException('机构类型未设置');
            }
            if (!in_array($this->params['organCode'], [0, 1])) {
                throw new TpamException('机构类型错误');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 法人身份证正面
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
                throw new TpamException('法人身份证正面未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 法人身份证反面
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
                throw new TpamException('法人身份证反面未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 机构证件照片
     * 营业执照图片地址
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

    public function IsOrganDocumentsUrlSet()
    {
        try {
            if (!(array_key_exists('organDocumentsUrl', $this->params) && !empty($this->params['organDocumentsUrl']))) {
                throw new TpamException('机构证件照未设置');
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

}