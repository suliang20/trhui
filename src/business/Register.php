<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/8
 * Time: 18:44
 */

namespace trhui;

use trhui\data\Data;

class Register extends Data
{
    public static $logFile = '../../data/resister.log';

    public function push($data)
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
                if (!@touch(static::$logFile)) {
                    throw new TpamException('创建注册日志文件失败');
                }
                $datas = [];
            }
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
}