<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/4
 * Time: 19:29
 */

namespace trhui;

use trhui\data\ResultCode;
/**
 * 清算通返回数据处理
 * 返回示例
 * result:{"authed":"0","userId":"510","merUserId":"223","merOrderId":"20180104110358","parameter1":null,"mobile":"13000000003"}
 * sign:Mt1+E11JdFz4wBfeiECYpSs/nntG07xXxbieY328cMWuRMLCoFWd3JzqRcoSukwQdFKbdLVDo8LNhWkKwuQmK7IjOW8YwzWO7NuqnfCU+YhHZkYABQtZUM8N3VdCfNFN5Gkvduoy/wroRvolq+KUXO0Emi9oEG6ZT03TeKsKLA0=
 * code:100
 * msg:注册成功
 * date:1515063866137
 * Class Results
 * @package trhui
 */
class Results
{
    public $errors = array();

    /**
     * 招商清算通公钥路径
     * @var
     */
    public $tpamPublicKeyPath;
    /**
     * 招商清算通公钥
     * @var
     */
    protected $tpamPublicKey;

    /**
     * 时间戳
     * @var
     */
    protected $date;
    /**
     * 签名，由code+msg+date+result根据私钥生成如果有参数为null，签名串中应当做空字符串("")来处理
     * @var
     */
    protected $sign;
    /**
     * 结果代码
     * @var
     */
    protected $code;
    /**
     * 结果信息
     * @var
     */
    protected $msg;
    /**
     * 结果字符串，json格式
     * @var
     */
    protected $result;

    /**
     * 处理入口
     * @param $data
     * @return bool
     */
    final public function Handle($data)
    {
        try {
            if (empty($data) || !is_array($data)) {
                throw new TpamException('数据错误');
            }
            foreach ($data as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    throw new TpamException('数据异常');
                }
                $this->$key = $value;
            }
            //  验证签名
            if (!$this->checkSign()) {
                throw new TpamException('验签失败');
            }
            //  获取返回参数
            $values = $this->getValues();
            if (!$values) {
                throw new TpamException('获取数据失败');
            }
            //  结果处理
            $this->ResultProcess($values);

            return $values;
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('handle', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 返回结果处理
     * @param $data
     * @return bool
     */
    public function ResultProcess()
    {
        try {
            var_dump(ResultCode::RESULT_CODE[$this->code]);

//            $values = $this->getValues();
            $result = $this->getResult();
            var_dump($result);
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('handle', $e->getMessage());
            }
        }
    }

    /**
     * 获取接口请求返回公共参数
     * @return array
     */
    final public function getValues()
    {
        try {
            if (!$this->checkValues()) {
                throw new TpamException('验证返回参数失败');
            }
            return [
                'result' => $this->result,
                'code' => $this->code,
                'sign' => $this->sign,
                'msg' => $this->msg,
                'date' => $this->date,
            ];
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('getValues', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 验证返回参数
     * @return bool
     */
    final protected function checkValues()
    {
        try {
            if (empty($this->code)) {
                throw new TpamException('结果代码不能为空');
            }
            if (empty($this->result)) {
                throw new TpamException('结果字符串不能为空');
            }
            if (empty($this->sign)) {
                throw new TpamException('签名信息不能为空');
            }
            if (empty($this->date)) {
                throw new TpamException('时间戳不能为空');
            }
            return true;
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('getValues', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 获取返回业务数据
     * @return bool|mixed
     */
    final public function getResult()
    {
        try {
            if (!$this->checkValues()) {
                throw new TpamException('验证返回参数失败');
            }
            $result = json_decode($this->result, true);
            if (!$result) {
                throw new TpamException('JSON解码失败');
            }
            return $result;
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('getValues', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 验证签名
     * @throws \Exception
     */
    final protected function checkSign()
    {
        try {
            //  返回签名
            if (empty($this->sign)) {
                throw new TpamException('签名错误');
            }
            //  验证数据拼装
            $string = $this->code . $this->msg . $this->date . $this->result;
            //  验证公钥文件
            if (!file_exists($this->tpamPublicKeyPath)) {
                throw new TpamException('清算通公钥文件不存在');
            }
            //  获取公钥
            $this->tpamPublicKey = @file_get_contents($this->tpamPublicKeyPath);
            if (empty($this->tpamPublicKey)) {
                throw new TpamException('清算通公钥不能为空');
            }
            //转换为openssl密钥，必须是经过pkcs8转换的私钥
            $res = openssl_pkey_get_public($this->tpamPublicKey);
            if (!$res) {
                throw new TpamException('转换密钥失败');
            }
            //  验签
            if (openssl_verify($string, base64_decode($this->sign), $res) != 1) {
                throw new TpamException('验签失败');
            }
            return true;
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('checkSign', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 输出JSON数据
     * @return string
     */
    final public function toJson()
    {
        try {
            $values = $this->getValues();
            if (!$values) {
                throw new TpamException('获取参数值失败');
            }
            return json_encode($values);
        } catch (TpamException $e) {
            if (!$this->hasErrors()) {
                $this->addError('toRegister', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * 检查错误
     * @return bool
     */
    final protected function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * 添加错误
     * @param $name
     * @param $error
     */
    final protected function addError($name, $error)
    {
        $this->errors[$name] = $error;
    }
}