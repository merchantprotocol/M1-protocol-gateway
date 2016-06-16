<?php

/**
 * @author Merchant Protocol (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class MP_Gateway_Model_Adminhtml_Observer
{	
	/**
	 * 
     * @param Varien_Event_Observer $observer
     * @see adminhtml_customer_save_after
     * @return Varien_Event_Observer
	 */
    public function adminhtmlCustomerSaveAfter($observer)
    {
    	$request = $observer->getRequest();
    	$customer = $observer->getCustomer();
    	
    	if ($data = $request->getPost()){
  		
    		// Unset template data
    		if (isset($data['card']['_template_'])) {
    			unset($data['card']['_template_']);
    		}    		
    		
    		foreach ($data['card'] as $index => $cardData){
    			
    			$card = Mage::getModel('mp_gateway/card');
    			$card->load($index);
    			$card->setCustomerId($customer->getId());
    			$datetime = date('Y-m-d H:i:s');
    			if (!$card->getId()){
    				$card->setCreatedAt($datetime);
    			}
    			$isDefault = isset($data['account']['default_card']) && $data['account']['default_card'] == $index;
    			$card->setIsDefault($isDefault);
    			
    			$method = Mage::getModel('mp_gateway/payment');
    			
    		}
    	}

    	return $observer;
    }
    

}