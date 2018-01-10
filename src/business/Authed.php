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
    public static $logFile = ROOT . '/data/authed.log';

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