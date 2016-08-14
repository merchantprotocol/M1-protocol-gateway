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
 * Class MP_Debug_UtilController
 */
class MP_Debug_UtilController extends MP_Debug_Controller_Front_Action
{

    /**
     * Search grouped class
     */
    public function searchGroupClassAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->getResponse()->setHttpResponseCode(405);
            return;
        }

        $uri = (string)$this->getRequest()->getPost('uri');
        $groupType = $this->getRequest()->getPost('group');

        $groupTypes = array($groupType);
        if ($groupType == 'all') {
            $groupTypes = array('model', 'block', 'helper');
        }

        $items = array();

        if ($uri) {
            foreach ($groupTypes as $type) {
                $items[$type]['class'] = Mage::getConfig()->getGroupedClassName($type, $uri);
                $items[$type]['filepath'] = mageFindClassFile($items[$type]['class']);
            }

            $block = $this->getLayout()->createBlock('mp_debug/array');
            $block->setTemplate('mp_debug/grouped_class_search.phtml');
            $block->assign('items', $items);
            $this->getResponse()->setBody($block->toHtml());
        } else {
            $this->getResponse()->setBody($this->__('Please fill in a search query'));
        }
    }


    /**
     * Flushes cache
     */
    public function flushCacheAction()
    {
        try {
            $this->getService()->flushCache();
            $this->getSession()->addSuccess('Cache flushed.');
        } catch (Exception $e) {
            $message = $this->__('Cache cannot be flushed: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Enables Full Page Cache Debug
     */
    public function enableFPCDebugAction()
    {
        try {
            $this->getService()->setFPCDebug(1);
            $this->getService()->flushCache();

            $message = $this->__('FPC debug was enabled');
            $this->getSession()->addSuccess($message);
        } catch (Exception $e) {
            $message = $this->__('FPC debug cannot be enabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Disables Full Page Cache Debug
     */
    public function disableFPCDebugAction()
    {
        try {
            $this->getService()->setFPCDebug(0);
            $this->getService()->flushCache();

            $message = $this->__('FPC debug was disabled');
            $this->getSession()->addSuccess($message);
        } catch (Exception $e) {
            $message = $this->__('FPC debug cannot be disabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Enables template Hints
     */
    public function enableTemplateHintsAction()
    {
        try {
            $this->getService()->setTemplateHints(1);
            $this->getService()->flushCache();
            // no need to notify customer - it's obvious if they were enabled

        } catch (Exception $e) {
            $message = $this->__('Template hints cannot be enabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Disable template hints
     */
    public function disableTemplateHintsAction()
    {
        try {
            $this->getService()->setTemplateHints(0);
            $this->getService()->flushCache();

        } catch (Exception $e) {
            $message = $this->__('Template hints cannot be disabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Enable inline translation
     */
    public function enableTranslateAction()
    {
        try {
            $this->getService()->setTranslateInline(1);
            $this->getService()->flushCache();
        } catch (Exception $e) {
            $message = $this->__('Translate inline cannot be enabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }


    /**
     * Disables inline translation
     */
    public function disableTranslateAction()
    {
        try {
            $this->getService()->setTranslateInline(0);
            $this->getService()->flushCache();

        } catch (Exception $e) {
            $message = $this->__('Translate inline cannot be disabled: %s', $e->getMessage());
            $this->getSession()->addError($message);
        }

        $this->_redirectReferer();
    }

}
