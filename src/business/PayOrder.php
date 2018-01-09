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

class PayOrder extends Data
{
    public static $logFile = ROOT . '/data/pay_order.log';

    public function push($merOrderId, $data)
    {
        try {
            if (empty($merOrderId)) {
                throw new TpamException('商户订单号不存在');
            }
            if (!$data['payeeUserList']) {
                throw new TpamException('收款人列表不存在');
            }
            $payeeUserList = $data['payeeUserList'];
            foreach ($payeeUserList as $item) {
                $orderId = $item['orderId'];
                $payOrder = [
                    'merOrderId' => $merOrderId,
                    'platformOrderId' => 0,
                    'orderId' => $orderId,
                    'payeeUserId' => $item['payeeUserId'],
                    'payeeAmount' => $item['payeeAmount'],
                    'feeToMerchant' => $item['feeToMerchant'],
                    'transferType' => $item['transferType'],
                    'feeType' => $item['feeType'],
                    'status' => 0,
                ];
                if (file_exists(static::$logFile)) {
                    $datas = file_get_contents(static::$logFile);
                    $datas = unserialize($datas);
                } else {
                    if (!touch(static::$logFile)) {
                        throw new TpamException('创建支付订单日志文件失败');
                    }
                    $datas = [];
                }
                if (empty($datas[$orderId])) {
                    $datas[$orderId] = $payOrder;
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

    public function update($data)
    {
        try {
            $merOrderId = $data['merOrderId'];
            $datas = $this->getAllOrder();
            if (!isset($datas[$merOrderId])) {
                throw new TpamException('订单不存在');
            }
            foreach ($datas as $key => $item) {
                if ($item['merOrderId'] == $merOrderId) {
                    $datas[$key]['platformOrderId'] = $data['platformOrderId'];
                    $datas[$key]['status'] = 1;
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

    public function getAllOrder()
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