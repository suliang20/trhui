<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/8
 * Time: 18:44
 */

namespace trhui;

use trhui\business\PayRequestOrder;
use trhui\business\Refund;
use trhui\data\Data;

class Request extends Data
{
    public static $logFile = ROOT . '/data/request.log';

    public function push($merOrderId, $data, $time)
    {
        try {
            if (file_exists(static::$logFile)) {
                $datas = file_get_contents(static::$logFile);
                $datas = unserialize($datas);
            } else {
                if (!@touch(static::$logFile)) {
                    throw new TpamException('创建请求日志文件失败');
                }
                $datas = [];
            }
            $data['request_time'] = $time;
            if (empty($datas[$merOrderId])) {
                $datas[$merOrderId] = $data;
            }
            switch ($data['serverCode']) {
                case 'orderTransfer':
                    $requestObj = new PayRequestOrder();
                    $res = $requestObj->push($merOrderId, $data, $time);
                    break;
                case 'refund':
                    $requestObj = new Refund();
                    $res = $requestObj->push($data, $time);
                    break;
                default:
                    $res = true;
            }
            if (!$res) {
                $this->errors = array_merge($this->errors, $requestObj->errors);
                throw new TpamException('订单记录失败');
            }
            $datas = serialize($datas);
            file_put_contents(static::$logFile, $datas);
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getAllRequest()
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

    public function getRequestByMerOrderId($merOrderId)
    {
        try {
            if (!file_exists(static::$logFile)) {
                throw new TpamException('请求文件不存在');
            }
            $datas = file_get_contents(static::$logFile);
            $datas = unserialize($datas);
            if (empty($datas[$merOrderId])) {
                throw new TpamException('请求订单号不存在');
            }
            return $datas[$merOrderId];
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}