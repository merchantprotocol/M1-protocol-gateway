<?php
/**
 * Merchant Protocol
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Merchant Protocol Commercial License (MPCL 1.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://merchantprotocol.com/commercial-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@merchantprotocol.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.merchantprotocol.com for more information.
 *
 * @category   MP
 * @package    MP_Gateway
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * @author Fran Mayers (https://merchantprotocol.com)
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
