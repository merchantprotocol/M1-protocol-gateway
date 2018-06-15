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

class MP_Gateway_Model_Vault extends MP_Gateway_Model_Payment
{

    /**
     * Transaction action codes
     */

    const TRXTYPE_VAULT_ADD         = 'add_customer';
    const TRXTYPE_VAULT_DEL         = 'delete_customer';

    /**
     * Add new card details to the customer vault
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param float $amount
     * @return MP_Gateway_Model_Vault
     */
    public function addDetails(Varien_Object $payment, $amount)
    {
        $request = $this->_buildPlaceRequest($payment, $amount);
        
        $request->setCustomerVault(self::TRXTYPE_VAULT_ADD);
        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        if ($response->getResultCode() == self::RESPONSE_CODE_APPROVED) {

            $card = Mage::getModel('mp_gateway/card')->addCard($request, $response);
            return $card;
        }

        return $this;
    }

    /**
     * Delete card details from the customer vault
     *
     * @param float $vaultId
     * @return MP_Gateway_Model_Vault
     */
    public function deleteDetails($vaultId)
    {
        if (!Mage::getModel('mp_gateway/card')->isValidVault($vaultId))
            Mage::throwException('Invalid Vault Id');

        $request = $this->_buildBasicRequest($payment, $amount);
        $request->setCustomerVault(self::TRXTYPE_VAULT_DEL);
        $request->setCustomerVaultId($vaultId);
        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        if ($response->getResultCode() == self::RESPONSE_CODE_APPROVED) {

            $card = Mage::getModel('mp_gateway/card')->deleteCard($vaultId);
        }

        return $this;
    }
}
