<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
     * Remove the card entry specified in token
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
