<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/2/22
 * Time: 13:46
 */

namespace trhui\extend;

use trhui\business\AccreditNew;
use trhui\business\DelayAutoPayday;
use trhui\business\ModifyPhone;
use trhui\business\PayResponse;
use trhui\business\Refund;
use trhui\business\RefundAll;
use trhui\business\Register;
use trhui\data\Data;
use trhui\data\DataBase;
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
                case DataBase::SERVER_TO_REGISTER:
                    $processObj = new Register();
                    break;
                case DataBase::SERVER_ORDER_TRANSFER:
                    $processObj = new PayResponse();
                    break;
                case DataBase::SERVER_REFUND:
                    $processObj = new Refund();
                    break;
                case DataBase::SERVER_REFUND_ALL:
                    $processObj = new RefundAll();
                    break;
                case DataBase::SERVER_DELAY_AUTO_PAYDAY:
                    $processObj = new DelayAutoPayday();
                    break;

                case DataBase::SERVER_MODIFY_PHONE:
                    $processObj = new ModifyPhone();
                    break;
                case DataBase::SERVER_ACCREDIT_NEW:
                    $processObj = new AccreditNew();
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