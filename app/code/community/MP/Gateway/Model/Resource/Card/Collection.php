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
