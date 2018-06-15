<?php
/**
 * Mage Plugins
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mage Plugins Commercial License (MPCL 1.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://mageplugins.net/commercial-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mageplugins@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future. If you wish to customize the extension for your
 * needs please refer to https://www.mageplugins.net for more information.
 *
 * @category   MP
 * @package    MP_Gateway
 * @copyright  Copyright (c) 2006-2018 Mage Plugins Inc. and affiliates (https://mageplugins.net/)
 * @license    https://mageplugins.net/commercial-license/  Mage Plugins Commercial License (MPCL 1.0)
 */

/**
 * @author Mage Plugins Team (https://mageplugins.net)
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
                if (!is_null($order) && $order->getCustomerId()){
                	$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                }
                    
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
        return ($customer && $customer->getId())  ? (int)$customer->getId() : false;
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
