<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:18
 */

namespace trhui\data;

class DataBase
{
    public $errors = array();
    protected $params = array();

    //  TODO    自定义参数1

    public function SetParameterl($value)
    {
        $this->params['parameterl'] = $value;
    }

    public function GetParameterl()
    {
        return $this->params['parameterl'];
    }

    /**
     * 输出Json数据
     * @return bool|string
     */
    public function toJson()
    {
        try {
            if (!$this->checkParams()) {
                throw new \trhui\TpamException('参数异常！');
            }
            return json_encode($this->params);
        } catch (\trhui\TpamException $e) {
            if (!$this->hasError()) {
                $this->addError('toJson', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 获取参数值
     */
    public function getParams()
    {
        try {
            if (!$this->checkParams()) {
                throw new \trhui\TpamException('参数异常！');
            }
            return $this->params;
        } catch (\trhui\TpamException $e) {
            if (!$this->hasError()) {
                $this->addError('getParams', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 检查参数
     * @return bool
     */
    public function checkParams()
    {
        try {
            if (!is_array($this->params) || count($this->params) <= 0) {
                throw new \trhui\TpamException('数组数据异常！');
            }
            foreach (get_class_methods($this) as $method) {
                if (preg_match('/^Is\w*Set$/', $method)) {
                    if (!$this->$method()) {
                        throw new \trhui\TpamException(substr($method, 2, -3) . '未设置！');
                    }
                }
            }
            return true;
        } catch (\trhui\TpamException $e) {
            if (!$this->hasError()) {
                $this->addError('checkParams', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 添加错误
     * @param $name
     * @param $errorMsg
     */
    public function addError($name, $errorMsg)
    {
        $this->errors[$name] = $errorMsg;
    }

    /**
     * 检查是否有错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->errors);
    }
}
