<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2018/1/6
 * Time: 19:21
 */

namespace trhui\data;
class Data
{
    public $errors = array();

    /**
     * 添加错误
     * @param $name
     * @param $errorMsg
     */
    public function addError($name, $errorMsg, $line = '', $file = '')
    {
        $this->errors[] = [
            'name' => $name,
            'errorMsg' => $errorMsg,
            'file' => $file,
            'line' => $line,
        ];
    }

    /**
     * 检查是否有错误
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}