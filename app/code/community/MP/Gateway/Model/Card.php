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

class MP_Gateway_Model_Card extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('mp_gateway/card');
    }

    public function getCustomerId()
    {
        return Mage::helper('mp_gateway')->getCustomerId();
    }

    /**
     * Return Collection of cards for current customer
     *
     * @return MP_Gateway_Model_Resource_Card_Collection
     */
    public function getCustomerCollection()
    {
        if (!($customerId = $this->getCustomerId()))
            return array();

        $collection = $this->getCollection()->addCustomerFilter($customerId);

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
     * Get card data text or html
     * @param string $type html or default text
     */
    public function format($type)
    {
    	$cardDataEnc = $this->getCardData();
    	$cardDataSer = Mage::getModel('core/encryption')->decrypt($cardDataEnc);
    	$cardDataArray = unserialize($cardDataSer);
    	$cardType = $cardDataArray['card_type'];
    	$cardTypeLabel = Mage::getConfig()->getNode('global/payment/cc/types/'.$cardType.'/name');
    	$last4 = 'XXXX-XXXX-XXXX-'.$cardDataArray['last4'];
    	$expmon = $cardDataArray['expmon'];
    	$expyear = $cardDataArray['expyear'];
    	$output = <<<EOT
{$cardTypeLabel}
{$last4}
{$expmon}/$expyear
EOT;
    	if ($type == 'html'){
    		$output = nl2br($output);
    	} 
    	
    	return $output;
    	
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
    public function getCardIcon($ccType = null)
    {
        if (is_null($ccType))
            $ccType = $this->getCardType();

        $types = Mage::getSingleton('payment/config')->getCcTypes();
        if (!isset($types[$ccType]))
            $ccType = 'unknown';

        $ccType = strtolower($ccType);

        $path = sprintf('mp_gateway/cards/cards_%s.png', $ccType);

        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $path;
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
        if (!($customerId = $this->getCustomerId()))
            return array();

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
        
        return $this;
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

    /**
     * Set the current loaded card as default
     *
     * @return boolean
     */
    public function setAsDefault()
    {
        return $this->getResource()->setAsDefault($this);
    }

}
