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

class MP_Gateway_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Return current customer (if frontend it's the logged in customer, if admin it's the loaded quote/order)
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            $customer = Mage::getSingleton('adminhtml/session_quote')->getCustomer();

            //If we are looking at an order in the backend
            if (is_null($customer->getId())) {
                $order = Mage::registry('current_order');
                if (!is_null($order))
                    return $order->getCustomerId();
            }
        }
        else {
            if (!Mage::getSingleton('customer/session')->isLoggedIn())
                return false;

            $customer = Mage::getSingleton('customer/session')->getCustomer();
        }

        return is_object($customer) && $customer->getId() ? $customer : false;
    }

    public function getCustomerId()
    {
        $customer = $this->getCustomer();
        return $customer ? (int)$customer->getId() : false;
    }

    /**
     * Check if current customer has enabled the option for saving cards
     *
     * @return boolean
     */
    public function isEnabledSavedCards()
    {
        $customer = $this->getCustomer();
        if (!$customer)
            return false;

        return (bool)$customer->getData('enable_savedcards');
    }

    /**
     * Check if the store enables the option for saving cards
     *
     * @return boolean
     */
    public function configEnabledSavedCards()
    {
        return (bool)Mage::getStoreConfig('payment/mp_gateway/enable_savedcards');
    }

    /**
     * Check if current customer is allowed to save cards
     *
     * @return boolean
     */
    public function canSaveCards()
    {
        return $this->configEnabledSavedCards() && $this->isEnabledSavedCards();
    }
}
