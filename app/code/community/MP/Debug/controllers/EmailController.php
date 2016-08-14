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
 * Class MP_Debug_EmailController
 */
class MP_Debug_EmailController extends MP_Debug_Controller_Front_Action
{

    /**
     * E-mail body action
     */
    public function getBodyAction()
    {
        if ($email = $this->_initEmail()) {
            $this->getResponse()->setHeader('Content-Type', $email->getIsPlain() ? 'text/plain' : 'text/html');
            $this->getResponse()->setBody($email->getBody());
        }
    }


    /**
     * Returns query references in request parameters
     *
     * @return MP_Debug_Model_Email
     */
    protected function _initEmail()
    {
        $token = $this->getRequest()->getParam('token');
        $index = $this->getRequest()->getParam('index');

        if ($token === null || $index === null) {
            $this->getResponse()->setHttpResponseCode(400)->setBody('Invalid parameters');
            return null;
        }

        /** @var MP_Debug_Model_RequestInfo $requestProfile */
        $requestProfile = Mage::getModel('mp_debug/requestInfo')->load($token, 'token');
        if (!$requestProfile->getId()) {
            $this->getResponse()->setHttpResponseCode(404)->setBody('Request profile not found');
            return null;
        }

        $emails = $requestProfile->getEmails();
        if (!$emails || !($index < count($emails))) {
            $this->getResponse()->setHttpResponseCode(404)->setBody('E-mail not found');
            return null;
        }

        return $emails[(int)$index];
    }

}
