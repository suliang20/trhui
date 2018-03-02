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

class AccountResults extends \trhui\Results
{
    /**
     * 返回结果处理
     * @param $data
     * @return bool
     */
    public function ResultProcess()
    {
        try {
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}