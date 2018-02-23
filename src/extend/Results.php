<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/2/22
 * Time: 13:46
 */

namespace trhui\extend;

use trhui\business\DelayAutoPayday;
use trhui\business\ModifyPhone;
use trhui\business\PayResponse;
use trhui\business\Refund;
use trhui\business\Register;
use trhui\data\ResultCode;
use trhui\Request;
use trhui\Response;
use trhui\TpamException;

class Results extends \trhui\Results
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
            $response = $this->getResult();

            $responseObj = new Response();
            $response['sign'] = $this->sign;
            $response['code'] = $this->code;
            $response['msg'] = $this->msg;
            $response['date'] = $this->date;
            $response['returnObj'] = $this->resultObj;
            if (!$responseObj->push($response['merOrderId'], $response)) {
                throw new TpamException('记录响应日志失败');
            }
            //  响应成功处理
            if ($this->code != 100) {
                throw new TpamException(ResultCode::RESULT_CODE[$this->code]);
            }
            $requestObj = new Request();
            if (!$requestData = $requestObj->getRequestByMerOrderId($response['merOrderId'])) {
                throw new TpamException('请求订单号不存在');
            }
            $processObj = null;
            switch ($requestData['serverCode']) {
                case 'toRegister':
                    $processObj = new Register();
                    break;
                case 'orderTransfer':
                    $processObj = new PayResponse();
                    break;
                case 'delayAutoPayday':
                    $processObj = new DelayAutoPayday();
                    break;
                case 'refund':
                    $processObj = new Refund();
                    break;
                case 'modifyPhone':
                    $processObj = new ModifyPhone();
                    break;
//                case 'toAuthen':
//
//                    break;
                default:
                    throw new TpamException('不存在的服务代码');
            }
            $res = $processObj->push($this->getResult(), $this->date);
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