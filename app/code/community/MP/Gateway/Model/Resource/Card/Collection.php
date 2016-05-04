<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class MP_Gateway_Model_Resource_Card_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('mp_gateway/card');
    }

    /**
     * Filter Collection by Customer ID
     *
     * @param int $customerId
     * @return MP_Gateway_Model_Resource_Card_Collection
     */

    public function addCustomerFilter($customerId = null)
    {
        if (is_null($customerId) && Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        }

        $this->getSelect()->where('customer_id = ?', (int)$customerId);

        return $this;
    }
}