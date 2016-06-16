<?php

/**
 * Class MP_Debug_Block_Config
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
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
