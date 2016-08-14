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
 * Class MP_Debug_Block_Util
 */
class MP_Debug_Block_Util extends MP_Debug_Block_Panel
{

    /**
     * Returns flush cache url
     *
     * @return string
     */
    public function getFlushCacheUrl()
    {
        return Mage::helper('mp_debug/url')->getFlushCacheUrl();
    }

    /**
     * Checks if template hints are currently enabled
     *
     * @return bool
     */
    public function isTemplateHintsEnabled()
    {
        return (bool)Mage_Core_Block_Template::getShowTemplateHints();
    }

    /**
     * Returns enable template hints url
     *
     * @return string
     */
    public function getEnableTemplateHintsUrl()
    {
        return Mage::helper('mp_debug/url')->getEnableTemplateHintsUrl();
    }

    /**
     * Returns disable template hints url
     *
     * @return string
     */
    public function getDisableTemplateHintsUrl()
    {
        return Mage::helper('mp_debug/url')->getDisableTemplateHintsUrl();
    }

    /**
     * Checks if with have Magento EE Full Page Cache
     *
     * @return bool
     */
    public function hasFullPageCache()
    {
        return $this->helper->isMagentoEE();
    }

    /**
     * Checks if Full Page Cache is enabled
     *
     * @return bool
     */
    public function isFPCDebugEnabled()
    {
        return (bool)Mage::getStoreConfig('system/page_cache/debug');
    }

    /**
     * Returns url to enable Full Page Cache Debug
     *
     * @return string
     */
    public function getEnableFPCDebugUrl()
    {
        return Mage::helper('mp_debug/url')->getEnableFPCDebugUrl();
    }

    /**
     * Returns url to disable Full Page Cache Debug
     *
     * @return string
     */
    public function getDisableFPCDebugUrl()
    {
        return Mage::helper('mp_debug/url')->getDisableFPCDebugUrl();
    }

    /**
     * Checks if inline translation is enabled
     *
     * @return bool
     */
    public function isTranslateInlineEnable()
    {
        return (bool)Mage::getSingleton('core/translate_inline')->isAllowed();
    }

    /**
     * Returns url to enable inline translation
     *
     * @return string
     */
    public function getEnableTranslateUrl()
    {
        return Mage::helper('mp_debug/url')->getEnableTranslateUrl();
    }

    /**
     * Returns url to disable inline translation
     *
     * @return string
     */
    public function getDisableTranslateUrl()
    {
        return Mage::helper('mp_debug/url')->getDisableTranslateUrl();
    }

    /**
     * Checks if SQL profiler is enabled
     *
     * @return bool
     */
    public function isSqlProfilerEnabled()
    {
        return $this->helper->getSqlProfiler()->getEnabled();
    }

    /**
     * Checks if Varien Profile is enabled
     *
     * @return bool
     */
    public function isVarienProfilerEnabled()
    {
        return $this->helper->canEnableVarienProfiler();
    }

    /**
     * Returns url to enable SQL profiler
     *
     * @return string
     */
    public function getEnableSqlProfilerUrl()
    {
        return Mage::helper('mp_debug/url')->getEnableSqlProfilerUrl();
    }

    /**
     * Returns url to disable SQL profiler
     *
     * @return string
     */
    public function getDisableSqlProfilerUrl()
    {
        return Mage::helper('mp_debug/url')->getDisableSqlProfilerUrl();
    }

    /**
     * Returns url to force activate Varien Profiler
     *
     * @return string
     */
    public function getEnableVarienProfilerUrl()
    {
        return Mage::helper('mp_debug/url')->getEnableVarienProfilerUrl();
    }

    /**
     * Returns url to disable force activation of Varien Profiler
     * @return string
     */
    public function getDisableVarienProfilerUrl()
    {
        return Mage::helper('mp_debug/url')->getDisableVarienProfilerUrl();
    }

}
