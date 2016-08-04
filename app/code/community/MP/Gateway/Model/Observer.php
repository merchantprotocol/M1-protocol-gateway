<?php

/**
 * @author Merchant Protocol (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class MP_Gateway_Model_Observer
{
	/**
	 * 
	 * @param unknown $observer
	 */
	public function saveQuoteDeposit($observer)
	{
		$quote = $observer->getQuote();
		$post = Mage::app()->getRequest()->getParam('deposit');
		
		var_dump($post);
		
		die('test');
	}
}