<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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