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

    public function push($merOrderId, $data, $time)
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
                    'payerUserId' => $data['payerUserId'],
                    'payeeUserId' => $item['payeeUserId'],
                    'payeeAmount' => $item['payeeAmount'],
                    'feeToMerchant' => $item['feeToMerchant'],
                    'transferType' => $item['transferType'],
                    'autoPayday' => isset($item['autoPayday']) ? $item['autoPayday'] : 0,
                    'feeType' => $item['feeType'],
                    'status' => -1,
                    'request_time' => $_SERVER['REQUEST_TIME'],
                    'pay_time' => 0,
                    'autoStatus' => -1,
                    'autoAuditDate' => 0,
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
                if (empty($datas[$merOrderId])) {
                    $datas[$merOrderId][$orderId] = $payOrder;
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

    public function update($data, $time)
    {
        try {
            $merOrderId = $data['merOrderId'];
            $datas = $this->getAllOrder();
            if (!isset($datas[$merOrderId])) {
                throw new TpamException('订单不存在');
            }
            foreach ($datas as $key => $value) {
                foreach ($value as $k => $item) {
                    if ($item['merOrderId'] == $merOrderId) {
                        if (!empty($data['platformOrderId'])) {
                            $datas[$key][$k]['platformOrderId'] = $data['platformOrderId'];
                        }
                        $datas[$key][$k]['status'] = $data['status'];
                        $datas[$key][$k]['pay_time'] = $time;
                    }
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
     * 自动审核时间更新
     * @param $data
     * @param $time
     * @return bool
     */
    public function delayAutoPaydayUpdate($data, $time)
    {
        try {
            $orderId = $data['originalOrderId'];
            $datas = $this->getAllOrder();
            foreach ($datas as $key => $value) {
                foreach ($value as $k => $item) {
                    if ($item['orderId'] == $orderId) {
                        if ($data['status'] == 0) {
                            $datas[$key][$k]['autoStatus'] = 0;
                            $datas[$key][$k]['autoAuditDate'] = $data['autoAuditDate'];
                        }
                    }
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

    public function getOneByMerOrderId($merUserId)
    {
        $orders = $this->getAllOrder();
//        var_dump($orders);exit;
        if (!isset($orders[$merUserId])) {
            return false;
        }
        return $orders[$merUserId];
    }

    /**
     * 获取付款订单
     * @param $merUserId
     * @param $orderId
     * @return bool
     */
    public function getPayOrder($merUserId, $orderId)
    {
        $orders = $this->getAllOrder();
        if (!isset($orders[$merUserId][$orderId])) {
            return false;
        }
        return $orders[$merUserId][$orderId];
    }

    /**
     * 支付订单全额退款
     * @param $merOrderId
     * @param $time
     * @return bool
     */
    public function payOrderRefundAll($merOrderId, $time)
    {
        try {
            $datas = $this->getAllOrder();
            if (!isset($datas[$merOrderId])) {
                throw new TpamException('订单不存在');
            }
            foreach ($datas as $key => $value) {
                foreach ($value as $k => $item) {
                    if ($item['merOrderId'] == $merOrderId) {
                        $datas[$key][$k]['refund_status'] = 1;
                        $datas[$key][$k]['refund_type'] = 1;
                        $datas[$key][$k]['refund_amount'] = $item['payeeAmount'] + $item['feeToMerchant'];
                        $datas[$key][$k]['refund_time'] = $time;
                    }
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
}
