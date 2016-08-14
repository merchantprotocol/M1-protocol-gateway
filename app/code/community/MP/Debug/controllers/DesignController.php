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
 * Class MP_Debug_DesignController
 */
class MP_Debug_DesignController extends MP_Debug_Controller_Front_Action
{

    /**
     * View layout handle details
     */
    public function viewHandleAction()
    {
        $area = $this->getRequest()->getParam('area');
        $storeId = (int)$this->getRequest()->getParam('store');
        $handle = $this->getRequest()->getParam('handle');

        $updatesByFile = $this->getService()->getFileUpdatesWithHandle($handle, $storeId, $area);
        $databaseUpdates = $this->getService()->getDatabaseUpdatesWithHandle($handle, $storeId, $area);

        $block = $this->getLayout()->createBlock('mp_debug/view', '', array(
            'template' => 'mp_debug/view/panel/_layout_updates.phtml',
            'file_updates' => $updatesByFile,
            'db_updates' => $databaseUpdates
        ));

        $this->getResponse()->setBody($block->toHtml());
    }


    /**
     * Returns layout handles for specified request profile
     */
    public function layoutUpdatesAction()
    {
        $token = $this->getRequest()->getParam('token');
        if (!$token) {
            return $this->getResponse()->setHttpResponseCode(400)->setBody('Invalid parameters');
        }

        /** @var MP_Debug_Model_RequestInfo $requestProfile */
        $requestProfile = Mage::getModel('mp_debug/requestInfo')->load($token, 'token');
        if (!$requestProfile->getId()) {
            return $this->getResponse()->setHttpResponseCode(404)->setBody('Request profile not found');
        }

        $layoutUpdates = $requestProfile->getDesign()->getLayoutUpdates();
        $this->renderArray($layoutUpdates, 'No Data', array('#', 'XML'));
    }

}
