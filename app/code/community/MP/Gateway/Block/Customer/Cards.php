<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Block_Customer_Cards extends Mage_Core_Block_Template
{
    /**
     * Retrieve list of saved cards
     *
     * @return MP_Gateway_Model_Resource_Card_Collection
     */
    public function getSavedCards()
    {
        return Mage::getModel('mp_gateway/card')->getCustomerCollection();
    }

    /**
     * Retrieve URL for switching card functionality
     *
     * @return string
     */
    public function getSwitchCards($value = 1)
    {
        return $this->getUrl('mp_gateway/cards/switch', array('value' => $value));
    }

    /**
     * Retrieve URL for setting a card as default
     *
     * @return string
     */
    public function getDefaultUrl($card)
    {
        return $this->getUrl('mp_gateway/cards/default', array('token' => $card->getCustomerVaultId()));
    }

    /**
     * Retrieve URL for card deletion
     *
     * @return string
     */
    public function getRemoveUrl($card)
    {
    	return $this->getUrl('mp_gateway/cards/remove', array('token' => $card->getCustomerVaultId()));
    }
}
