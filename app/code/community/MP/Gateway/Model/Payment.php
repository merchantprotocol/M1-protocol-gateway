<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Model_Payment extends Mage_Payment_Model_Method_Cc
{
    protected $_code  = 'mp_gateway';
    protected $_formBlockType = 'mp_gateway/form_gateway';
    //protected $_infoBlockType = 'payment/info_cc';

    /**
     * Gateway request URLs
     */
    const TRANSACTION_URL           = 'https://merchantprotocol.transactiongateway.com/api/transact.php';
    const TRANSACTION_URL_TEST_MODE = 'https://merchantprotocol.transactiongateway.com/api/transact.php';

    /**
     * Transaction action codes
     */
    const TRXTYPE_AUTH_ONLY         = 'auth';
    const TRXTYPE_SALE              = 'sale';
    const TRXTYPE_CREDIT            = 'credit';

    /**
     * Response codes
     */
    const RESPONSE_CODE_APPROVED                = 1;
    const RESPONSE_CODE_DECLINED                = 2;
    const RESPONSE_CODE_SYSERROR                = 3;

    /**
     * Availability options
     */
    protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = true;
    protected $_canRefund               = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid                 = true;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = true;
    protected $_canSaveCc = false;
    protected $_isProxy = false;
    protected $_canFetchTransactionInfo = true;

    /**
     * Gateway request timeout
     */
    protected $_clientTimeout = 45;


    /**
     * Authorize payment
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return MP_Gateway_Model_Payment
     */

    public function authorize(Varien_Object $payment, $amount)
    {
        $request = $this->_buildPlaceRequest($payment, $amount);
        $request->setType(self::TRXTYPE_AUTH_ONLY);
        $this->_setReferenceTransaction($payment, $request);
        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        switch ($response->getResultCode()){
            case self::RESPONSE_CODE_APPROVED:
                $payment->setTransactionId($response->getTransactionid())->setIsTransactionClosed(0);
                break;
            case self::RESPONSE_CODE_DECLINED:
                $payment->setTransactionId($response->getTransactionid())->setIsTransactionClosed(0);
                $payment->setIsTransactionPending(true);
                break;
        }
        return $this;
    }

    /**
     * Get capture amount
     *
     * @param float $amount
     * @return float
     */
    protected function _getCaptureAmount($amount)
    {
        $infoInstance = $this->getInfoInstance();
        $amountToPay = round($amount, 2);
        $authorizedAmount = round($infoInstance->getAmountAuthorized(), 2);
        return $amountToPay != $authorizedAmount ? $amountToPay : 0;
    }
    
    /**
      * If response is failed throw exception
      *
      * @throws Mage_Core_Exception
      */
    protected function _processErrors(Varien_Object $response)
    {
        if ($response->getResultCode() != self::RESPONSE_CODE_APPROVED) {
            Mage::throwException($response->getRespmsg());
        }
    }

    /**
     * Capture payment
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return MP_Gateway_Model_Payment
     */
    public function capture(Varien_Object $payment, $amount)
    {
        if ($payment->getReferenceTransactionId()) {
            $request = $this->_buildPlaceRequest($payment, $amount);
            $request->setTrxtype(self::TRXTYPE_SALE);
            $request->setOrigid($payment->getReferenceTransactionId());
        } elseif ($payment->getParentTransactionId()) {
            $request = $this->_buildBasicRequest($payment);
            $request->setOrigid($payment->getParentTransactionId());
            $captureAmount = $this->_getCaptureAmount($amount);
            if ($captureAmount) {
                $request->setAmount($captureAmount);
            }
            $trxType = $this->getInfoInstance()->hasAmountPaid() ? self::TRXTYPE_SALE : self::TRXTYPE_SALE;
            $request->setType($trxType);
        } else {
            $request = $this->_buildPlaceRequest($payment, $amount);
            $request->setType(self::TRXTYPE_SALE);
        }

        $response = $this->_postRequest($request);
        $this->_processErrors($response);

        switch ($response->getResultCode()){
            case self::RESPONSE_CODE_APPROVED:
                $payment->setTransactionId($response->getTransactionid())->setIsTransactionClosed(0);
                break;
            case self::RESPONSE_CODE_DECLINED:
                $payment->setTransactionId($response->getTransactionid())->setIsTransactionClosed(0);
                $payment->setIsTransactionPending(true);
                break;
        }
        return $this;
    }

    /**
     * Getter for URL to perform Gateway requests, based on test mode by default
     * 
     * @param bool $testMode Ability to specify test mode using
     * @return string
     */
    protected function _getTransactionUrl($testMode = null)
    {
        $testMode = is_null($testMode) ? $this->getConfigData('sandbox_flag') : (bool)$testMode;
        if ($testMode) {
            return self::TRANSACTION_URL_TEST_MODE;
        }
        return self::TRANSACTION_URL;
    }

     /**
      * Return request object with basic information for gateway request
      *
      * @param Mage_Sales_Model_Order_Payment $payment
      * @return Varien_Object
      */
    protected function _buildBasicRequest(Varien_Object $payment)
    {
        $request = new Varien_Object();
        $request
            ->setUsername($this->getConfigData('user'))
            ->setPassword($this->getConfigData('pwd'))
            ->setRequestId($this->_generateRequestId());
        return $request;
    }

     /**
      * Return unique value for request
      *
      * @return string
      */
    protected function _generateRequestId()
    {
        return Mage::helper('core')->uniqHash();
    }

    /**
      * Return request object with information for 'authorization' or 'sale' action
      *
      * @param Mage_Sales_Model_Order_Payment $payment
      * @param float $amount
      * @return Varien_Object
      */
    protected function _buildPlaceRequest(Varien_Object $payment, $amount)
    {
        $request = $this->_buildBasicRequest($payment);
        $request->setAmount(round($amount,2));
        $request->setCcnumber($payment->getCcNumber());
        $request->setCcexp(sprintf('%02d',$payment->getCcExpMonth()) . substr($payment->getCcExpYear(),-2,2));
        $request->setCvv($payment->getCcCid());

        $order = $payment->getOrder();
        if (!empty($order)) {
            $orderIncrementId = $order->getIncrementId();

            $request->setIpaddress($_SERVER['REMOTE_ADDR'])
                ->setCurrency($order->getBaseCurrencyCode())
                ->setTax($order->getTaxAmount())
                ->setShipping($order->getShippingAmount())
                ->setOrderid($order->getId())
                ->setPonumber(null)
                ->setOrderdescription($orderIncrementId);

            $customerId = $order->getCustomerId();
            if ($customerId) {
                $request->setCustref($customerId);
            }

            $billing = $order->getBillingAddress();
            if (!empty($billing)) {
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
                    ->setEmail($payment->getOrder()->getCustomerEmail());
            }
            $shipping = $order->getShippingAddress();
            if (!empty($shipping)) {
                $request->setShippingFirstname($shipping->getFirstname())
                    ->setShippingLastname($shipping->getLastname())
                    ->setShippingCompany($shipping->getCompany())
                    ->setShippingAddress1($shipping->getStreet(1))
                    ->setShippingAddress2($shipping->getStreet(2))
                    ->setShippingCity($shipping->getCity())
                    ->setShippingState($shipping->getRegionCode())
                    ->setShippingZip($shipping->getPostcode())
                    ->setShippingCountry($shipping->getCountry())
                    ->setShippingEmail($payment->getOrder()->getCustomerEmail());
            }
        } 
        return $request;
    }

    /**
     * Post request to gateway and return response
     *
     * @param Varien_Object $request
     * @return Varien_Object
     */
    protected function _postRequest(Varien_Object $request)
    {
        $result = new Varien_Object;

        $query = http_build_query($request->getData());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_getTransactionUrl());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_clientTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_clientTimeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($response = curl_exec($ch))) {
            return ERROR;
        }

        curl_close($ch);
        unset($ch);

        $valArray = explode('&', $response);

        foreach($valArray as $val) {
            $valArray2 = explode('=', $val);
            $result->setData(strtolower($valArray2[0]), $valArray2[1]);
        }

        $result->setResultCode($result->getResponse())
                ->setRespmsg($result->getResponsetext());

        $debugData['result'] = $result->getData();
        $this->_debug($debugData);

        return $result;
    }

    /**
     * Set reference transaction data into request
     *
     * @param Varien_Object $payment
     * @param Varien_Object $request
     * @return MP_Gateway_Model_Payment
     */
    protected function _setReferenceTransaction(Varien_Object $payment, $request)
    {
        return $this;
    }
}