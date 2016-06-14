<?php

/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
