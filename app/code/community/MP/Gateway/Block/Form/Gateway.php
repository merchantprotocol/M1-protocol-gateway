<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Block_Form_Gateway extends Mage_Payment_Block_Form_Cc
{

    /**
     * Retrive has verification configuration
     *
     * @return boolean
     */
    public function hasVerification()
    {
    	return true;
    }
}