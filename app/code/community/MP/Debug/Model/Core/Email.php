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
 
trait MP_Debug_Model_Core_Email_Capture
{
    /**
     * Calls parent's real send method
     *
     * @return $this
     */
    public function parentSend()
    {
        return parent::send();
    }


    /**
     * Overwrites parent method to capture details for sent e-mail
     *
     * @return MP_Debug_Model_Core_Email
     */
    public function send()
    {
        try {
            $this->captureEmail();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this->parentSend();
    }


    /**
     * Adds e-mail information on current request profile info
     */
    public function captureEmail()
    {
        $email = Mage::getModel('mp_debug/email');
        $email->setFromEmail($this->getFromEmail());
        $email->setFromName($this->getFromName());
        $email->setToEmail($this->getToEmail());
        $email->setToName($this->getToName());
        $email->setSubject($this->getSubject());
        $email->setIsPlain($this->getType() != 'html');
        $email->setBody($this->getBody());
        $email->setIsSmtpDisabled((bool)Mage::getStoreConfigFlag('system/smtp/disable'));
        $email->setIsAccepted(true);  // Assume e-mail is accepted

        $requestInfo = Mage::getSingleton('mp_debug/observer')->getRequestInfo();
        $requestInfo->addEmail($email);
    }

}


/**
 * Class MP_Debug_Model_Core_Email rewrites core/email and overwrites send() to capture any e-mail information.
 *
 * @method string getType()
 * @method string getFromEmail()
 * @method string getFromName()
 * @method string getToEmail()
 * @method string getToName()
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: Pirate MP, 2016
 * @link     https://piratesheep.com
 */


if (Mage::helper('core')->isModuleEnabled('Aschroder_SMTPPro')) {
    class MP_Debug_Model_Core_Email extends Aschroder_SMTPPro_Model_Email
    {
        use MP_Debug_Model_Core_Email_Capture;
    }
} else {

    class MP_Debug_Model_Core_Email extends Mage_Core_Model_Email
    {
        use MP_Debug_Model_Core_Email_Capture;
    }
}
