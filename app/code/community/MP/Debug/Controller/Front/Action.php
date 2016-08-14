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
 * Class MP_Debug_Controller_Front_Action
 */
class MP_Debug_Controller_Front_Action extends Mage_Core_Controller_Front_Action
{

    /**
     * Prevent access to our access if toolbar is disabled
     *
     * @throws Zend_Controller_Response_Exception
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('mp_debug')->isAllowed()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->getResponse()->setHttpResponseCode(404);
        }
    }


    /**
     * Returns current session
     *
     * @return Mage_Core_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('core/session');
    }


    /**
     * Returns an instance to our all known service
     *
     * @return MP_Debug_Model_Service
     */
    public function getService()
    {
        return Mage::getModel('mp_debug/service');
    }


    /**
     * Renders specified array
     *
     * @param array $data
     * @param string $noDataLabel   Label when array is empty.
     * @param null $header          An array with column label.
     * @return string
     */
    public function renderArray(array $data, $noDataLabel = 'No Data', $header = null)
    {
        /** @var MP_Debug_Block_View $block */
        $block = $this->getLayout()->createBlock('mp_debug/view');
        $html = $block->renderArray($data, $noDataLabel, $header);

        $this->getResponse()->setHttpResponseCode(200)->setBody($html);
    }


    /**
     * Renders specified table (array of arrays)
     *
     * @param array $data
     * @param array $fields
     * @param string $noDataLabel
     * @return string
     */
    public function renderTable(array $data, array $fields = array(), $noDataLabel = 'No Data')
    {
        /** @var MP_Debug_Block_View $block */
        $block = $this->getLayout()->createBlock('mp_debug/view');
        $html = $block->renderArrayFields($data, $fields, $noDataLabel);

        $this->getResponse()->setHttpResponseCode(200)->setBody($html);
    }

}
