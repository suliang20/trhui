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

class ToWithdraw extends Data
{
    public static $logFile = ROOT . '/data/to-withdraw.log';

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
                    throw new TpamException('创建结算日志文件失败');
                }
                $datas = [];
            }
            if (empty($datas[$merOrderId])) {
                $params = json_decode($data['params'], true);

                $order['merOrderId'] = $merOrderId;
                $order['userId'] = $params['userId'];
                $order['amount'] = $params['amount'];
                $order['feePayer'] = $params['feePayer'];
                $order['feeToMerchant'] = $params['feeToMerchant'];
                $order['isNeedForAudit'] = $params['isNeedForAudit'];
                $order['fee'] = 0;
                $order['platformOrderId'] = '';
                $order['status'] = -1;
                $order['requestTime'] = $time;
                $order['responseTime'] = 0;
                $datas[$merOrderId] = $order;
            } else {
                $datas[$merOrderId]['status'] = $data['status'];
                $datas[$merOrderId]['platformOrderId'] = $data['platformOrderId'];
                $datas[$merOrderId]['responseTime'] = $time;
            }
//            var_dump($datas);exit;
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