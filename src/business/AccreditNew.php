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

class AccreditNew extends Data
{
    public static $logFile = ROOT . '/data/accredit-new.log';

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
                    throw new TpamException('创建认证日志文件失败');
                }
                $datas = [];
            }

            if (empty($datas[$merOrderId])) {
                $params = json_decode($data['params'], true);
                $params['status'] = -1;
                $params['requestTime'] = $time;
                $params['responseTime'] = 0;
                unset($params['notifyUrl'], $params['frontUrl']);
                $datas[$merOrderId] = $params;
            } else {
                $datas[$merOrderId]['status'] = 1;
                $datas[$merOrderId]['responseTime'] = $time;
                $accreditInfo = $datas[$merOrderId];
                $userId = $accreditInfo['userId'];
                $accreditType = $accreditInfo['accreditType'];
                unset($accreditInfo['userId']);
                unset($accreditInfo['status']);
                unset($accreditInfo['requestTime']);
                unset($accreditInfo['responseTime']);

//                var_dump($accreditInfo);exit;
                $registerObj = new Register();
                if (!$registerObj->updateAccredit($userId, $accreditType, $accreditInfo, $time)) {
                    throw new TpamException('更新授权数据失败');
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
}