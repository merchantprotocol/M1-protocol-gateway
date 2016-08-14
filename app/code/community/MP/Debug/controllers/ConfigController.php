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
 * Class MP_Debug_ConfigController
 */
class MP_Debug_ConfigController extends MP_Debug_Controller_Front_Action
{
    /**
     * PHP info Action
     */
    public function phpinfoAction()
    {
        phpinfo();
    }


    /**
     * Download configuration as text or xml action
     */
    public function downloadAction()
    {
        $type = $this->getRequest()->getParam('type', 'xml');
        /** @var Mage_Core_Model_Config_Element $configNode */
        $configNode = Mage::app()->getConfig()->getNode();

        switch ($type) {
            case 'txt';
                $this->downloadAsText($configNode);
                break;
            case 'xml':
            default:
                $this->downloadAsXml($configNode);
        }
    }


    /**
     * Force enable Varien Profiler action
     */
    public function enableVarienProfilerAction()
    {
        $this->getService()->setVarienProfilerStatus(1);
        $this->getService()->flushCache();

        $this->_redirectReferer();
    }


    /**
     * Disable forced activation of Varien Profiler
     */
    public function disableVarienProfilerAction()
    {
        $this->getService()->setVarienProfilerStatus(0);
        $this->getService()->flushCache();

        $this->_redirectReferer();
    }


    /**
     * Prepares response with configuration as text
     *
     * @param Mage_Core_Model_Config_Element $configNode
     */
    public function downloadAsText(Mage_Core_Model_Config_Element $configNode)
    {
        $items = array();
        Mage::helper('mp_debug')->xml2array($configNode, $items);

        $content = '';
        foreach ($items as $key => $value) {
            $content .= "$key = $value\n";
        }

        $this->_prepareDownloadResponse('config.txt', $content, 'text/plain');
    }


    /**
     * Prepares response with configuration as xml
     *
     * @param Mage_Core_Model_Config_Element $configNode
     */
    public function downloadAsXml(Mage_Core_Model_Config_Element $configNode)
    {
        $this->_prepareDownloadResponse('config.xml', $configNode->asXML(), 'text/xml');
    }

}
