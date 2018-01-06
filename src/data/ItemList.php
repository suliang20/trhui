<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2017/12/29
 * Time: 18:21
 */

namespace trhui\data;

use trhui\TpamException;

/**
 * 商品列表（不校验金额，仅校验数据格式，用于以后数据挖掘）
 * Class ItemList
 * @package trhui\data
 */
class ItemList extends DataBase
{
    /**
     * 商品编号
     * @param $value
     */
    public function SetItemId($value)
    {
        $this->params['itemId'] = $value;
    }

    public function GetItemId()
    {
        return $this->params['itemId'];
    }

    public function IsItemIdSet()
    {
        try {
            if (!(array_key_exists('itemId', $this->params) && !empty($this->params['itemId']))) {
                throw new TpamException('商品编号未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 商品名称
     * 如果itemList为空则不校验，不为空则需校验
     * @param $value
     */
    public function SetItemName($value)
    {
        $this->params['itemName'] = $value;
    }

    public function GetItemName()
    {
        return $this->params['itemName'];
    }

    public function IsItemNameSet()
    {
        try {
            if (!(array_key_exists('itemName', $this->params) && !empty($this->params['itemName']))) {
                throw new TpamException('商品名称未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 商品数量
     * 整数
     * 如果itemList为空则不校验，不为空则需校验
     * @param $value
     */
    public function SetItemQty($value)
    {
        $this->params['itemQty'] = $value;
    }

    public function GetItemQty()
    {
        return $this->params['itemQty'];
    }

    public function IsItemQtySet()
    {
        try {
            if (!(array_key_exists('itemQty', $this->params) && !empty($this->params['itemQty']))) {
                throw new TpamException('商品数量未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }

    /**
     * 商品价格
     * 单位:分（可为0）
     * @param $value
     */
    public function SetItemAmount($value)
    {
        $this->params['itemAmount'] = $value;
    }

    public function GetItemAmount()
    {
        return $this->params['itemAmount'];
    }

    public function IsItemAmountSet()
    {
        try {
            if (!(array_key_exists('itemAmount', $this->params) && !empty($this->params['itemAmount']))) {
                throw new TpamException('商品价格未设置');
            }
            return true;
        } catch (TpamException $e) {
            $this->addError(__FUNCTION__, $e->getMessage(), $e->getFile(), $e->getLine());
        }
        return false;
    }
}