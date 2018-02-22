<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 15:21
 */

namespace trhui\extend;

use trhui\Request;
use trhui\Tpam;
use trhui\TpamException;

class TpamExtend extends Tpam
{
    /**
     * 请求处理
     */
    public function Process()
    {
        try {
            //  添加请求日志
            $requestObj = new Request();
            if (!$requestObj->push($this->merOrderId, $this->getValues(), $this->date)) {
                $this->errors = array_merge($this->errors, $requestObj->errors);
                throw new TpamException('添加请求数据失败');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}