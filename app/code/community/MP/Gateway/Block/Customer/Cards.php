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
