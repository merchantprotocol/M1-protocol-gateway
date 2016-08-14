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
 * Class MP_Debug_Model_Email
 */
class MP_Debug_Model_Email
{
    protected $fromEmail;
    protected $fromName;
    protected $toEmail;
    protected $toName;
    protected $subject;
    protected $isPlain;
    protected $body;
    protected $variables = array();
    protected $isSmtpDisabled;
    protected $isAccepted = false;

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param mixed $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @return mixed
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @param mixed $toEmail
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
    }

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @param mixed $toName
     */
    public function setToName($toName)
    {
        $this->toName = $toName;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getIsPlain()
    {
        return $this->isPlain;
    }

    /**
     * @param mixed $isPlain
     */
    public function setIsPlain($isPlain)
    {
        $this->isPlain = $isPlain;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return boolean
     */
    public function isIsSmtpDisabled()
    {
        return $this->isSmtpDisabled;
    }

    /**
     * @param boolean $isSmtpDisabled
     */
    public function setIsSmtpDisabled($isSmtpDisabled)
    {
        $this->isSmtpDisabled = $isSmtpDisabled;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     *
     * TODO: Iterate over arrays or varien objects to capture more data
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        // get them into a serializable state
        $data = array();
        foreach ($variables as $variableName => $variable) {
            if (is_scalar($variable)) {
                $data[$variableName] = $variable;
            } else if (is_array($variable)) {
                $data[$variableName] = array_keys($variable);
            } else if (is_object($variable)) {
                $data[$variableName]  = get_class($variable);
            } else {
                $data[$variableName] = (string)$variable;
            }
        }

        $this->variables = $data;
    }

    /**
     * @return boolean
     */
    public function isAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * @param boolean $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }

}
