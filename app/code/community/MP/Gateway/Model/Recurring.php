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

class MP_Gateway_Model_Recurring extends MP_Gateway_Model_Payment
{
    /**
     * Transaction action codes
     */
    const TRXTYPE_RECUR_PLA         = 'add_plan';
    const TRXTYPE_RECUR_ADD         = 'add_subscription';
    const TRXTYPE_RECUR_DEL         = 'delete_subscription';


    /**
     * Add new recurring subscription
     *
     * @param Mage_Payment_Model_Recurring_Profile $profile
     * @param Mage_Payment_Model_Info $paymentInfo
     * @return MP_Gateway_Model_Recurring
     */
    public function addSubscription(Mage_Payment_Model_Recurring_Profile $profile, Mage_Payment_Model_Info $paymentInfo)
    {
        $request = new Varien_Object();

        $request = $this->_buildBasicRequest($profile);
        $request->setRecurring(self::TRXTYPE_RECUR_ADD);

        $request->setPlanPayments($profile->getPeriodMaxCycles());
        $request->setPlanAmount($profile->getBillingAmount());

        $periodFrequency = $profile->getPeriodFrequency();
        switch ($profile->getPeriodUnit()) {
            case 'day' : $request->setDayFrequency($periodFrequency); break;
            case 'week' : $request->setDayFrequency($periodFrequency * 7); break;
            case 'semi_month' : $request->setDayFrequency($periodFrequency * 15); break;
            case 'month' : $request->setMonthFrequency($periodFrequency); break;
            case 'year' : $request->setMonthFrequency($periodFrequency * 12); break;
        }
        
        $request->setStartDate(date('Ymd', strtotime($profile->getStartDatetime()) + 86400));

        $request->setCctype($paymentInfo->getCcType());
        $request->setCcnumber($paymentInfo->getCcNumber());
        $request->setCcexp(sprintf('%02d',$paymentInfo->getCcExpMonth()) . substr($paymentInfo->getCcExpYear(),-2,2));

        $billing = $profile->getBillingAddressInfo();
        if (!empty($billing)) {

        	if (!is_object($billing))
        		$billing = Varien_Object::setData($billing);

            $request->setFirstname($billing->getFirstname())
                ->setLastname($billing->getLastname())
                ->setCompany($billing->getCompany())
                ->setAddress1($billing->getStreet(1))
                ->setAddress2($billing->getStreet(2))
                ->setCity($billing->getCity())
                ->setState($billing->getRegionCode())
                ->setZip($billing->getPostcode())
                ->setCountry($billing->getCountry())
                ->setTelephone($billing->getTelephone())
                ->setFax($billing->getFax())
                ->setCustomerEmail($billing->getEmail());
        }
     
        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        #Zend_Debug::dump($response); exit;
        if ($response->getResultCode() == self::RESPONSE_CODE_APPROVED) {
        	$profile->setState(Mage_Sales_Model_Recurring_Profile::STATE_ACTIVE)
        		->setTransactionId($response->getTransactionid())
        		->setSubscriptionId($response->getSubscriptionId());
        }
        else {
            $profile->setState(Mage_Sales_Model_Recurring_Profile::STATE_PENDING)
        		->setTransactionId($response->getTransactionid());
        }

        return $this;
    }

    /**
     * Delete recurring subscription
     *
     * @param float $vaultId
     * @return MP_Gateway_Model_Vault
     */
    public function deleteSubscription($subId)
    {
        $request->setCustomerVault(self::TRXTYPE_RECUR_DEL);
        $request->setSubscriptionId($subId);

        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        if ($response->getResultCode() == self::RESPONSE_CODE_APPROVED) {
        }

        return $this;
    }
}
