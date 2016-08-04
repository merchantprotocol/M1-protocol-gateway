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
    		
    		if (isset($data['card'])){
    			// Unset template data
    			if (isset($data['card']['_template_'])) {
    				unset($data['card']['_template_']);
    			}
    			
    			$cardCollection = Mage::getModel('mp_gateway/card')->getCollection();
    			$cardCollection->addFieldToFilter('customer_id',$customer->getId());
    			$cardIds =  $cardCollection->getColumnValues('entity_id');
    			$customerCardIds = array();
    			
    			foreach ($data['card'] as $id => $cardData){
    				
    				$isDefault = isset($data['account']['default_card']) && $data['account']['default_card'] == $id;
    				//update card
    				if (is_numeric($id)){
    					
    					if (in_array($id, $cardIds)){
    						$card = Mage::getModel('mp_gateway/card');
    						$card->load($id);
    							
    						$cardCustomerId = $card->getData('customer_id');
    						if ($cardCustomerId != $customer->getId()){
    							Mage::log("Card {$card->getId()} customer {$card->getCustomerId()} do not match customer {$customer->getId()}",Zend_Log::ERR,'payment_mp_gateway.log');
    							continue;
    						}
    							
    						$card->setIsDefault($isDefault);

    						//update exp year and month
    						$cardDataEnc = $card->getCardData();
    						$cardDataSer = Mage::getModel('core/encryption')->decrypt($cardDataEnc);
    						$cardDataArray = unserialize($cardDataSer);
    						$cardDataArray['expmon'] = $cardData['expmon'];
    						$cardDataArray['expyear'] = $cardData['expyear'];
    						$cardDataSer = serialize($cardDataArray);
    						$cardDataEnc = Mage::getModel('core/encryption')->encrypt($cardDataSer);
    						$card->setCardData($cardDataEnc);
    						
    						$card->save(); 
    						$customerCardIds[] = $card->getId();
    					}     					
    				//new card
    				} else {
    					
    					Mage::getSingleton('adminhtml/session_quote')->setCustomer($customer);
    					$quote = Mage::getModel('sales/quote')->assignCustomer($customer);
    						
    					$payment = $quote->getPayment();
    					
    					$payment->setCcType ($cardData['card_type'])
    					->setCcNumber ($cardData['card_number'])
    					->setCcExpMonth($cardData['expmon'])
    					->setCcExpYear($cardData['expyear'])
    					->setCcCid($cardData['cid']);
    						
    					$vault = Mage::getModel('mp_gateway/vault');
    					
    					$card = $vault->addDetails($payment, 0.01);
    					$card->setIsDefault($isDefault);
    					$card->save();
    					$customerCardIds[] = $card->getId();
    				}
    			}
    			
    			//remove deleted cards
    			foreach ($cardCollection as $card){
    				if (!in_array($card->getId(), $customerCardIds)){
    					$card->delete();
    				}
    			}
    				
    		}
    		
    	}

    	return $observer;
    }
    

}