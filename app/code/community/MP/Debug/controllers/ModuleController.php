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
 * Class MP_Debug_ModuleController
 */
class MP_Debug_ModuleController extends MP_Debug_Controller_Front_Action
{

    /**
     * Enables specified module
     */
    public function enableAction()
    {
        $moduleName = (string)$this->getRequest()->getParam('module');

        try {
            $this->getService()->setModuleStatus($moduleName, true);
            $this->getService()->flushCache();
            Mage::getSingleton('core/session')->addSuccess('Module was enabled.');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to enable module: ' . $e->getMessage());
        }

        $this->_redirectReferer();
    }


    /**
     * Disables specified module
     */
    public function disableAction()
    {

        $moduleName = (string)$this->getRequest()->getParam('module');

        try {
            $this->getService()->setModuleStatus($moduleName, false);
            $this->getService()->flushCache();
            Mage::getSingleton('core/session')->addSuccess('Module was disabled.');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to disable module: ' . $e->getMessage());
        }

        $this->_redirectReferer();
    }

}
