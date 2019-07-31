<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2019/7/31
 * Time: 9:47
 */

/**
 * 数据合并
 * @param $a
 * @param $b
 * @return array|mixed
 */
function merge($a, $b)
{
    $args = func_get_args();
    $res = array_shift($args);
    while (!empty($args)) {
        foreach (array_shift($args) as $k => $v) {
            if ($v instanceof \yii\helpers\UnsetArrayValue) {
                unset($res[$k]);
            } elseif ($v instanceof \yii\helpers\ReplaceArrayValue) {
                $res[$k] = $v->value;
            } elseif (is_int($k)) {
                if (array_key_exists($k, $res)) {
                    $res[] = $v;
                } else {
                    $res[$k] = $v;
                }
            } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                $res[$k] = merge($res[$k], $v);
            } else {
                $res[$k] = $v;
            }
        }
    }
    return $res;
}

/**
 * 判断是否POST提交
 * @return bool
 */
function is_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
}

/**
 * 判断是否GET提交
 * @return bool
 */
function is_get()
{
    return $_SERVER['REQUEST_METHOD'] == 'get' ? true : false;
}

/**
 * 获取域名根目录
 * @return string
 */
function GetCurrentUrl()
{
    $url = GetCurUrl();
    $url = explode('?', $url)[0];
    $parseArr = parse_url($url);
    if (!empty($parseArr['path'])) {
        $arr = explode('/', $url);
        array_pop($arr);
        $url = implode('/', $arr);
    }
    return $url . '/';
}


/**
 * 获取当前URL
 * @return string
 */
function GetCurUrl()
{
    $url = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $url = 'https://';
    }
    if ($_SERVER['SERVER_PORT'] != '80') {
        $url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
    } else {
        $url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }
    return $url;
}

