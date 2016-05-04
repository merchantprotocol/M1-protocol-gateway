<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Model_Card extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('mp_gateway/card');
    }

    /**
     * Return Collection of cards for current customer
     *
     * @return MP_Gateway_Model_Resource_Card_Collection
     */

    public function getCustomerCollection()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn())
            return array();

        $collection = $this->getCollection()->addCustomerFilter();

        $result = array();

        foreach ($collection as $card):

            $cardData = $card->getCardData();
            $cardDataArray = unserialize(Mage::getModel('core/encryption')->decrypt($cardData));

            if ($cardDataArray !== false)
                $card->addData($cardDataArray);

            array_push($result, $card);

        endforeach;

        return $result;
    }

    /**
     * Display Card Entry HTML
     *
     */
    public function render()
    {
        return Mage::app()->getLayout()->createBlock('core/template')
            ->setData('card', $this)
            ->setTemplate('mp_gateway/customer/cards/item.phtml')
            ->toHtml();
    }

    /**
     * Retrieve credit card type name
     *
     * @return string
     */
    public function getCcTypeName()
    {
        $types = Mage::getSingleton('payment/config')->getCcTypes();
        $ccType = $this->getCardType();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }
        return (empty($ccType)) ? Mage::helper('payment')->__('N/A') : $ccType;
    }

    /**
     * Return URL to Card Icon based on its type
     *
     * @return string
     */
    public function getCardIcon($ccType)
    {   
        if (is_null($ccType))
            $ccType = $this->getCardType();

        $types = Mage::getSingleton('payment/config')->getCcTypes();
        if (!isset($types[$ccType]))
            $ccType = 'unknown';

        $ccType = strtolower($ccType);

        $path = sprintf('mp_gateway/images/cards_%s.png', $ccType);

        return Mage::getDesign()->getSkinUrl($path);
    }

    /**
     * Add cart entry
     *
     * @param Varien_Object $request
     * @param Varien_Object $response
     * @return MP_Gateway_Model_Card
     */
    public function addCard($request, $response)
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn())
            return $this;

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $this->setCustomerId($customerId);

        $cardDataArray = array(
            'customer_vault_id' => $response->getCustomerVaultId(),
            'card_type' => $request->getCctype(),
            'last4' => substr($request->getCcnumber(), -4),
            'expmon' => $request->getCcexpmon(),
            'expyear' => $request->getCcexpyear()
        );

        $cardData = Mage::getModel('core/encryption')->encrypt(serialize($cardDataArray));

        $this->setCardData($cardData)->save();
    }

    /**
     * Delete cart entry
     *
     * @param varchar $vaultId
     * @return boolean
     */
    public function deleteCard($vaultId)
    {
        $cards = $this->getCustomerCollection();

        foreach ($cards as $card) {
            if ($card->getCustomerVaultId() == $vaultId)
                $card->delete();
        }

        return true;
    }

    /**
     * Return card by vault ID
     *
     * @param varchar $vaultId
     * @return boolean
     */
    public function getCardByVaultId($vaultId)
    {
        $cards = $this->getCustomerCollection();

        foreach ($cards as $card) {
            if ($card->getCustomerVaultId() == $vaultId)
                return $card;
        }

        return false;
    }

    /**
     * Check if the vault ID belongs to this customer
     *
     * @param varchar $vaultId
     * @return boolean
     */
    public function isValidVault($vaultId)
    {
        return $this->getCardByVaultId($vaultId) !== false;
    }
}