<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/8
 * Time: 18:44
 */

namespace trhui\business;

use trhui\data\Data;
use trhui\TpamException;

class PayResponse extends Data
{
    public static $logFile = ROOT . '/data/pay-response.log';

    public function push($data, $time)
    {
        try {
            if (empty($data['merOrderId'])) {
                throw new TpamException('商户订单不存在');
            }
            $merOrderId = $data['merOrderId'];
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                if (!touch(static::$logFile)) {
                    throw new TpamException('创建支付日志文件失败');
                }
                $datas = [];
            }
            if (empty($datas[$merOrderId])) {
                $datas[$merOrderId] = $data;
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            $payRequestOrderObj = new PayRequestOrder();
            if (!$payRequestOrderObj->update($data, $time)) {
                $this->errors = array_merge($this->errors, $payRequestOrderObj->errors);
                throw new TpamException('更新请求订单失败');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getAllRegister()
    {
        try {
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                $datas = [];
            }
            return $datas;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getUserByMerUserId($merUserId)
    {
        try {
            if (!file_exists(static::$logFile)) {
                throw new TpamException('注册文件不存在');
            }
            $datas = file_get_contents(static::$logFile);
            $datas = unserialize($datas);
            if (empty($datas[$merUserId])) {
                throw new TpamException('商户用户ID不存在');
            }
            return $datas[$merUserId];
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getNewMerUserId()
    {
        $allUser = $this->getAllRegister();
        if (empty($allUser)) {
            return 1000;
        } else {
            return max(array_keys($allUser)) + 1;
        }
    }

    public function hasMobile($mobile)
    {
        $allUser = $this->getAllRegister();
        foreach ($allUser as $value) {
            if ($value['mobile'] == $mobile) {
                return true;
            }
        }
        return false;
    }

    /**
     * 验证手机号码格式
     * @param unknown $mobile
     * @return boolean
     */
    public static function chkMobile($mobile)
    {
        $search = '/^1[3-9]\d{9}$/';
        if (preg_match($search, $mobile)) {
            return true;
        } else {
            return false;
        }
    }
}