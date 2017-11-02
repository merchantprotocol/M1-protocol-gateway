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
 * Used in creating options for Yes|No config value selection
 *
 */
class MP_Gateway_Model_Source_Config_Payment_Methods
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
    	$options = array();
    	foreach($this->getMethods() as $method)
    	{
    		$_option = array(
    				'value' => $method->getId(),
    				'label' => Mage::getStoreConfig('payment/'.$method->getId().'/title') ?: $method->getId()
    		);
    		$options[] = $_option;
    	}
    	return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
    	$options = array();
    	foreach($this->getMethods() as $method)
    	{
    		$options[] = $method->getId();
    	}
    	return $options;
    }
	
    /**
     * 
     */
    protected function getMethods()
    {
    	return Mage::getModel('payment/config')->getAllMethods(Mage::app()->getStore()->getId());
    }
}
