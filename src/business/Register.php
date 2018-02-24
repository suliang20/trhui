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

class Register extends Data
{
    public static $logFile = ROOT . '/data/register.log';

    public function push($data, $time)
    {
        try {
            if (empty($data['merUserId'])) {
                throw new TpamException('商户用户ID不存在');
            }
            $merUserId = $data['merUserId'];
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                if (!touch(static::$logFile)) {
                    throw new TpamException('创建注册日志文件失败');
                }
                $datas = [];
            }
            $data['register_time'] = $time;
            $data['update_time'] = $time;
            if (empty($datas[$merUserId])) {
                $datas[$merUserId] = $data;
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getAll()
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

    /**
     * 根据商户用户ID获取用户数据
     * @param $merUserId
     * @return bool
     */
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

    /**
     * 根据清算通用户ID获取用户数据
     * @param $userId
     * @return bool
     */
    public function getUserByUserId($userId)
    {
        try {
            $allUser = $this->getAll();
            foreach ($allUser as $value) {
                if (isset($value['userId']) && $value['userId'] == $userId) {
                    return $value;
                }
            }
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 获取新的商户用户ID
     * @return int|mixed
     */
    public function getNewMerUserId()
    {
        $allUser = $this->getAll();
        if (empty($allUser)) {
            return 1000;
        } else {
            return max(array_keys($allUser)) + 1;
        }
    }

    /**
     * 检查手机号是否已存在
     * @param $mobile
     * @return bool
     */
    public function hasMobile($mobile)
    {
        $allUser = $this->getAll();
        foreach ($allUser as $value) {
            if (isset($value['merUserId']) && $value['mobile'] == $mobile) {
                return true;
            }
        }
        return false;
    }

    /**
     * 检查清算通用户ID是否存在
     * @param $userId
     * @return bool
     */
    public function hasUserId($userId)
    {
        $allUser = $this->getAll();
        foreach ($allUser as $value) {
            if (isset($value['merUserId']) && $value['userId'] == $userId) {
                return true;
            }
        }
        return false;
    }

    /**
     * 修改手机号
     * @param $userId
     * @param $newPhone
     * @return bool
     */
    public function modifyPhone($userId, $newPhone, $time)
    {
        try {
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                if (!touch(static::$logFile)) {
                    throw new TpamException('创建注册日志文件失败');
                }
                $datas = [];
            }
            foreach ($datas as $key => $value) {
                if ($value['userId'] == $userId) {
                    $datas[$key]['mobile'] = $newPhone;
                    $datas[$key]['update_time'] = $time;
                }
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 更新授权数据
     * @param $userId
     * @param $accreditType
     * @param $data
     * @param $time
     * @return bool
     */
    public function updateAccredit($userId, $accreditType, $data, $time)
    {
        try {
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                if (!touch(static::$logFile)) {
                    throw new TpamException('创建注册日志文件失败');
                }
                $datas = [];
            }
            foreach ($datas as $key => $value) {
                if ($value['userId'] == $userId) {
                    $datas[$key]['accredit'][$accreditType] = $data;
                    $datas[$key]['update_time'] = $time;
                }
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
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