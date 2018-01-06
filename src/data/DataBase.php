<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:18
 */

namespace trhui\data;

use trhui\TpamException;

/**
 * 参数基类
 * Class DataBase
 * @package trhui\data
 */
class DataBase extends Data
{
    protected $params = array();

    /**
     * 服务接口
     * @var
     */
    protected $serverInterface;

    /**
     * 业务类型编码
     * @var
     */
    protected $serverCode;

    //  TODO    自定义参数1

    public function SetParameter1($value)
    {
        $this->params['parameter1'] = $value;
    }

    public function GetParameter1()
    {
        return $this->params['parameter1'];
    }

    /**
     * 获取服务接口
     * @return mixed
     */
    public function getServerInterface()
    {
        return $this->serverInterface;
    }

    /**
     * 获取业务类型编码
     * @return mixed
     */
    public function getServerCode()
    {
        return $this->serverCode;
    }

    /**
     * 输出Json数据
     * @return bool|string
     */
    public function toJson()
    {
        try {
            if (!$this->checkParams()) {
                throw new TpamException('参数异常！');
            }
            return json_encode($this->params);
        } catch (TpamException $e) {
            $this->addError('toJson', $e->getMessage());
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
                throw new TpamException('参数异常！');
            }
            return $this->params;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
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
            foreach (get_class_methods($this) as $method) {
                if (preg_match('/^Is\w*Set$/', $method)) {
                    if (!$this->$method()) {
                        throw new TpamException(substr($method, 2, -3) . '未设置！');
                    }
                }
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}
