<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
     * Retrieve can save card configuration
     *
     * @return boolean
     */
    public function canSaveCard()
    {
    	return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    /**
     * Retrieve list of saved cards
     *
     * @return MP_Gateway_Model_Resource_Card_Collection
     */
    public function getSavedCards()
    {
        return Mage::getModel('mp_gateway/card')->getCustomerCollection();
    }
}