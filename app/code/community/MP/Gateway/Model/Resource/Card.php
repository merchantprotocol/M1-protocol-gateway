<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Model_Resource_Card extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('mp_gateway/card', 'entity_id');
    }

    /**
     * Set the current loaded card as default
     *
     * @return boolean
     */
    public function setAsDefault($card)
    {
    	if (is_null($card->getCustomerId()))
    		return false;

    	//Reset all the default params
        $this->_getWriteAdapter()->update($this->getMainTable(), array('is_default' => 0), array('customer_id = ?' => $card->getCustomerId()));

        //Set the current card to be the default one
        $this->_getWriteAdapter()->update($this->getMainTable(), array('is_default' => 1), array('entity_id = ?' => $card->getEntityId()));

        return true;
    }
}