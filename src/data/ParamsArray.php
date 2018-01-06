<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;

use trhui\TpamException;


class ParamsArray extends Data
{
    public $paramsArr = [];


    public function SetParams(DataBase $inputObj, $params)
    {
        try {
            if (empty($params)) {
                throw new TpamException('参数不能为空');
            }
            if (!is_array($params)) {
                throw new TpamException('数据结构异常');
            }
            foreach ($params as $key => $value) {
                if (method_exists($inputObj, 'Set' . lcfirst($key))) {
                    $method = 'Set' . lcfirst($key);
                    $inputObj->$method($value);
                }
                if (method_exists($inputObj, 'Set' . ucfirst($key))) {
                    $method = 'Set' . ucfirst($key);
                    $inputObj->$method($value);
                }
            }
            $params = $inputObj->getParams();
            if (!$params) {
                $this->errors = array_merge($this->errors, $inputObj->errors);
                throw new TpamException('获取参数失败');
            }
            $this->paramsArr[] = $params;
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    public function getParamsJson()
    {
        return json_encode($this->paramsArr);
    }

    public function getParamsArr()
    {
        return $this->paramsArr;
    }


}