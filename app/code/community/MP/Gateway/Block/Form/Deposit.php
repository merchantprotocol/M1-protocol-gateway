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
 * @package    MP_Gateway
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * @author Fran Mayers (https://merchantprotocol.com)
 */

class MP_Gateway_Block_Form_Deposit extends Mage_Core_Block_Template
{
	protected $_configpath = 'payment/mp_gateway_deposit/';
	
	/**
	 * (non-PHPdoc)
	 * @see Mage_Payment_Block_Form_Cc::_construct()
	 */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mp_gateway/form/deposit.phtml');
    }
    
    /**
     * 
     * @return boolean
     */
    public function isActive()
    {
    	return (bool)Mage::getStoreConfig($this->_configpath.'active');
    }
    
    /**
     * 
     * @return boolean
     */
    public function getTitle()
    {
    	return (string)Mage::getStoreConfig($this->_configpath.'title');
    }
    
    /**
     * 
     * @return boolean
     */
    public function getDescription()
    {
    	return (string)Mage::getStoreConfig($this->_configpath.'description');
    }
    
    /**
     * 
     * @return array
     */
    public function getCcAvailableTypes()
    {
    	$sources = Mage::getModel('paygate/authorizenet_source_cctype')->toOptionArray();
    	$allowed = explode(',', (string)Mage::getStoreConfig('payment/mp_gateway/cctypes'));
    	foreach ($sources as $key => $source)
    	{
    		if (in_array($source['value'], $allowed)) continue;
    		unset($sources[$key]);
    	}
    	return $sources;
    }
	
    /**
     * 
     */
    public function getActiveMethods()
    {
    	return array_keys(Mage::getModel('payment/config')->getActiveMethods(Mage::app()->getStore()->getId()));
    }
    
    /**
     * 
     * @return array
     */
    public function getMethods()
    {
    	return explode(',', $this->getMethodsString());
    }
    
    /**
     * 
     * @return string
     */
    public function getMethodsString()
    {
    	return Mage::getStoreConfig($this->_configpath.'methods');
    }
    
    /**
     * 
     * @return array
     */
    public function getExcludedMethods()
    {
    	return array_diff($this->getActiveMethods(), $this->getMethods());
    }
	
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->isActive()) return;
        return parent::_toHtml();
    }

    /**
     * Retrieve payment configuration object
     *
     * @return Mage_Payment_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            $months = array_merge($months, $this->_getConfig()->getMonths());
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
            $years = array(0=>$this->__('Year'))+$years;
            $this->setData('cc_years', $years);
        }
        return $years;
    }

    /*
    * Whether switch/solo card type available
    */
    public function hasSsCardType()
    {
        $availableTypes = $this->getCcAvailableTypes();
        $ssPresenations = array_intersect(array('SS', 'SM', 'SO'), $availableTypes);
        if ($availableTypes && count($ssPresenations) > 0) {
            return true;
        }
        return false;
    }
}
