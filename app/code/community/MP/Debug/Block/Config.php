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
 * @package    MP_Debug
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * Class MP_Debug_Block_Config
 */
class MP_Debug_Block_Config extends MP_Debug_Block_Panel
{

    /**
     * Returns version for Magento
     *
     * @return string
     */
    public function getMagentoVersion()
    {
        return Mage::helper('mp_debug/config')->getMagentoVersion();
    }


    /**
     * Checks if Magento Developer Mode is enabled
     *
     * @return bool
     */
    public function isDeveloperMode()
    {
        return $this->helper->getIsDeveloperMode();
    }


    /**
     * Returns an array with statuses for PHP extensions required by Magento
     *
     * @return array
     */
    public function getExtensionStatus()
    {
        return Mage::helper('mp_debug/config')->getExtensionStatus();
    }


    /**
     * Returns a string representation for current store (website name and store name)
     *
     * @return string
     */
    public function getCurrentStore()
    {
        $currentStore = $this->_getApp()->getStore();
        return sprintf('%s / %s', $currentStore->getWebsite()->getName(),  $currentStore->getName());
    }

}
