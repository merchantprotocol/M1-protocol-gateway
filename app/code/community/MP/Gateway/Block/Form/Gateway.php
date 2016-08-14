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

class MP_Gateway_Block_Form_Gateway extends Mage_Payment_Block_Form_Cc
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mp_gateway/form/gateway.phtml');
    }

    /**
     * Retrieve has verification configuration
     *
     * @return boolean
     */
    public function hasVerification()
    {
    	return true;
    }
    
    /**
     * 
     * 
     * @return boolean
     */
    public function forceSavedCards()
    {
    	return (bool)Mage::getStoreConfig('payment/mp_gateway/force');
    }

    /**
     * Retrieve can save card configuration
     *
     * @return boolean
     */
    public function canSaveCard()
    {
    	return Mage::helper('mp_gateway')->canSaveCards();
    }

    /**
     * Retrieve list of saved cards
     *
     * @return MP_Gateway_Model_Resource_Card_Collection
     */
    public function getSavedCards()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if (!(bool)Mage::getStoreConfig('payment/mp_gateway/enable_savedcards') || $quote->hasRecurringItems())
            return;

        return Mage::getModel('mp_gateway/card')->getCustomerCollection();
    }
}
