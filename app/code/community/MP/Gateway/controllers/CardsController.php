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
 * @author Fran Mayers (https://mageplugins.net)
 */

class MP_Gateway_CardsController extends Mage_Core_Controller_Front_Action
{
    /**
     * Fetch customer session
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Check if the user is logged in
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    /**
     * Display the list of saved cards
     */
    public function indexAction()
    {
        $this->loadLayout();

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Saved Cards'));

        $this->renderLayout();
    }

    /**
     * Switch the main functionality as a customer level
     */
    public function switchAction()
    {
        $value = $this->getRequest()->getParam('value');
        if (is_null($value))
            $value = 1;

        try {
            $customer = Mage::helper('mp_gateway')->getCustomer();

            $customer->setData('enable_savedcards', $value)->save();
            $this->_getSession()->addSuccess(sprintf('The card functionality has been successfully %s', ($value) ? 'enabled' : 'disabled'));
        } catch (Exception $e) {
            $this->_getSession()->addError('Error in the request, please try again');
        }

        return $this->_redirectReferer();
    }

    /**
     * Set the card entry specified in token as default
     */
    public function defaultAction()
    {
        $token = $this->getRequest()->getParam('token');
        if (!$token || !($card = Mage::getModel('mp_gateway/card')->getCardByVaultId($token))) {
            $this->_getSession()->addError('Please select a card entry to set as default');
            return $this->_redirectReferer();
        }

        try {
            $card->setAsDefault();
            $this->_getSession()->addSuccess('The card has been successfully set as default');
        } catch (Exception $e) {
            $this->_getSession()->addError('Error in the request, please try again');
        }

        return $this->_redirectReferer();
    }

    /**
     * Remove the card entry specified in token
     */
    public function removeAction()
    {
        $token = $this->getRequest()->getParam('token');
        if (!$token) {
            $this->_getSession()->addError('Please select a card entry to remove');
            return $this->_redirectReferer();
        }

        try {
            Mage::getModel('mp_gateway/vault')->deleteDetails($token);
            $this->_getSession()->addSuccess('The card has been successfully deleted');
        } catch (Exception $e) {
            $this->_getSession()->addError('Error in deletion, please try again');
        }

        return $this->_redirectReferer();
    }

}
