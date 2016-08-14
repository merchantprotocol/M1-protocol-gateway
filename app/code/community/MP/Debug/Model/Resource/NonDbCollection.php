<?php
/**
 * Merchant Protocol
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Merchant Protocol Commercial License (MPCL 1.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://merchantprotocol.com/commercial-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@merchantprotocol.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.merchantprotocol.com for more information.
 *
 * @category   MP
 * @package    MP_Debug
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */
 
/**
 * Class MP_Debug_Model_Resource_NonDbCollection
 */
class MP_Debug_Model_Resource_NonDbCollection extends Varien_Data_Collection
{
    protected $_filters = array();

    /**
     * 
     * @param unknown $attribute
     * @param string $condition
     */
    public function addFieldToFilter($attribute, $condition=null)
    {
        if (isset($condition['eq'])) {
            $value  = $condition['eq'];
            $method = 'equal';
        } elseif (isset($condition['like'])) {
            $value  = $condition['like'];
            $method = 'like';        
        }
        
        $this->_filters[] = array(
                            'attribute' => $attribute,
                            'method'    => $method,
                            'value'     => $value,
                        );        
    }
    
    /**
     * (non-PHPdoc)
     * @see Varien_Data_Collection::load()
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }    

        $this->_addFilterToCollection();               
        $this->_sortCollection();
        $this->_renderLimit();
        
        $this->_setIsLoaded();
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see Varien_Data_Collection::getSize()
     */
    public function getSize()
    {
        if (is_null($this->_totalRecords)) {
            $this->_totalRecords = count($this->_items);
        }
        return intval($this->_totalRecords);
    }
    
    /**
     * (non-PHPdoc)
     * @see Varien_Data_Collection::_renderLimit()
     */
    protected function _renderLimit()
    {
        if($this->_pageSize){
            $from = ($this->getCurPage() - 1) * $this->_pageSize;
            $to  =  ($this->getCurPage() - 1) * $this->_pageSize + $this->_pageSize - 1;
            $items = $this->_items;
            $this->_items = array();
            $counter = 0;
            foreach($items as $item) {
                if ($counter >= $from && $counter <= $to) {
                    $this->addItem($item);
                }
                $counter++;
            }
        }

        return $this;
    }
    
    /**
     * 
     * @return MP_Debug_Model_Resource_NonDbCollection
     */
    protected function _sortCollection()
    {
        usort($this->_items , array($this, '_doCompare'));
        return $this;
    }
    
    /**
     * 
     */
    protected function _addFilterToCollection()
    {
        $items = $this->_items;
        $this->_items = array();
        foreach($items as $item) {
            if ($this->_filterItem($item)) {
                $this->addItem($item);
            }
        }
    }
    
    /**
     * 
     * @param unknown $item
     * @return boolean
     */
    protected function _filterItem($item)
    {
        foreach ($this->_filters as $filter) {
            $method = $filter['method'];
            $attribute = $filter['attribute'];
            $itemValue = $item[$attribute];
            
            if (is_array($itemValue) && isset($itemValue['filter_condition'])) {
                $itemValue = $itemValue['filter_condition'];
            }

            if (!$this->$method($itemValue, $filter['value'])) {
                return false;
            }
        }
        
        return true;
    }
 
    /**
     * 
     * @param unknown $item
     * @param unknown $column
     * @return string
     */
    protected function _getColumnsValue($item, $column)
    {
        $value = $item->getData($column);
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        return $value;
    }
 
    /**
     * 
     * @param unknown $a
     * @param unknown $b
     * @return number
     */
    protected function _doCompare($a, $b)
    {
        foreach($this->_orders as $column => $order) {                                    
            $valueA = $this->_getColumnsValue($a, $column);
            $valueB = $this->_getColumnsValue($b, $column);

            $result = strcmp($valueA, $valueB);

            if (strtolower($order) == 'asc') {
                $result = -$result;
            }
            
            return $result;
        }
        return 0;
    }
 
    /**
     * 
     * @param unknown $filerValue
     * @param unknown $needle
     * @return boolean
     */
    public function equal($filerValue, $needle)
    {
        return ($filerValue == $needle);
    }
    
    /**
     * 
     * @param unknown $filerValue
     * @param unknown $needle
     * @return string
     */
    public function like($filerValue, $needle)
    {
        $needle = trim($needle, ' \'"%');
        return stristr($filerValue, $needle);
    }    
}
