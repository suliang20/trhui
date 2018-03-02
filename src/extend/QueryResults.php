<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/2/22
 * Time: 13:46
 */

namespace trhui\extend;

use trhui\business\DelayAutoPayday;
use trhui\business\PayRequestOrder;
use trhui\business\PayResponse;
use trhui\business\Refund;
use trhui\business\Register;
use trhui\data\ResultCode;
use trhui\Request;
use trhui\Response;
use trhui\TpamException;

class QueryResults extends \trhui\Results
{
    /**
     * 返回结果处理
     * @param $data
     * @return bool
     */
    public function ResultProcess()
    {
        try {
            //  记录响应日志
            $return = $this->getResult();

            //  响应成功处理
            if ($this->code != 100) {
                throw new TpamException(ResultCode::RESULT_CODE[$this->code]);
            }
            $requestObj = new Request();
            if (empty($return['originalMerOrderId']) || !$requestData = $requestObj->getRequestByMerOrderId($return['originalMerOrderId'])) {
                var_dump($return);
                throw new TpamException('请求订单号不存在');
            }
//            var_dump($return);
//            var_dump($requestData);
//            exit;

            $processObj = null;
            switch ($requestData['serverCode']) {
                case 'orderTransfer':
                    $processObj = new PayRequestOrder();
                    break;
                default:
                    throw new TpamException('不存在的服务代码');
            }
            $res = $processObj->queryUpdate($this->getResult(), $this->date);
            if (!$res) {
                $this->errors = array_merge($this->errors, $processObj->errors);
                throw new TpamException('返回结果处理失败');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}