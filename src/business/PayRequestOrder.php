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

class PayRequestOrder extends Data
{
    public static $logFile = ROOT . '/data/pay_request_order.log';

    public function push($merOrderId, $data, $time)
    {
        try {
            if (empty($merOrderId)) {
                throw new TpamException('商户订单号不存在');
            }
            $params = json_decode($data['params'], true);
            $requestOrder = [
                'merOrderId' => $merOrderId,
                'platformOrderId' => 0,
                'request_amount' => $params['amount'],
                'response_amount' => 0,
                'payerUserId' => $params['payerUserId'],
                'actionType' => $params['actionType'],
                'transferPayType' => $params['transferPayType'],
                'topupType' => $params['topupType'],
                'feePayer' => $params['feePayer'],
                'status' => 0,
                'response_status' => -1,
                'remarks' => '',
                'request_parameter1' => !empty($data['parameter1']) ? $data['parameter1'] : '',
                'response_parameter1' => '',
                'request_time' => $time,
                'pay_time' => 0,
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
                $datas[$merOrderId] = $requestOrder;
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            $payOrderObj = new PayOrder();
            if (!$payOrderObj->push($merOrderId, $params, $time)) {
                $this->errors = array_merge($this->errors, $payOrderObj->errors);
                throw new TpamException('返回结果处理失败');
            }
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
            $datas = $this->getAll();
            if (!isset($datas[$merOrderId])) {
                throw new TpamException('订单不存在');
            }
            $datas[$merOrderId]['platformOrderId'] = $data['platformOrderId'];
            $datas[$merOrderId]['response_amount'] = $data['amount'];
            $datas[$merOrderId]['response_status'] = $data['status'];
            if ($data['status'] == 1) {
                $datas[$merOrderId]['status'] = 1;
                $datas[$merOrderId]['pay_time'] = $time;
            }
            $datas[$merOrderId]['response_parameter1'] = $data['parameter1'];
            if (isset($data['remarks'])) {
                $datas[$merOrderId]['remarks'] = $data['remarks'];
            }

            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            $payOrderObj = new PayOrder();
            if (!$payOrderObj->update($data, $time)) {
                $this->errors = array_merge($this->errors, $payOrderObj->errors);
                throw new TpamException('返回结果处理失败');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function queryUpdate($data, $time)
    {
        try {
            $merOrderId = $data['originalMerOrderId'];
            $datas = $this->getAll();
            if (!isset($datas[$merOrderId])) {
                throw new TpamException('订单不存在');
            }
            if (!empty($data['platformOrderId'])) {
                $datas[$merOrderId]['platformOrderId'] = $data['platformOrderId'];
            }
            $datas[$merOrderId]['response_amount'] = $data['amount'];
            $datas[$merOrderId]['response_status'] = $data['status'];
            if ($data['status'] == 1) {
                $datas[$merOrderId]['status'] = 1;
                $datas[$merOrderId]['pay_time'] = $time;
            }

            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            $payOrderObj = new PayOrder();
            $data['merOrderId'] = $data['originalMerOrderId'];
            if (!$payOrderObj->update($data, $time)) {
                $this->errors = array_merge($this->errors, $payOrderObj->errors);
                throw new TpamException('返回结果处理失败');
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