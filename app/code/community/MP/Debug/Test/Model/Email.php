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
 * @package    MP_Subscription
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * Class MP_Debug_Test_Model_Email
 *
 * @covers MP_Debug_Model_Email
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Email extends EcomDev_PHPUnit_Test_Case
{

    public function testSetters()
    {
        $model = Mage::getModel('mp_debug/email');

        $model->setFromEmail('mario@mailinator.com');
        $this->assertEquals('mario@mailinator.com', $model->getFromEmail());

        $model->setFromName('Mario O');
        $this->assertEquals('Mario O', $model->getFromName());

        $model->setToEmail('mario+to@mailinator.com');
        $this->assertEquals('mario+to@mailinator.com', $model->getToEmail());

        $model->setToName('Mario To');
        $this->assertEquals('Mario To', $model->getToName());

        $model->setSubject('Some subject');
        $this->assertEquals('Some subject', $model->getSubject());

        $model->setIsPlain(true);
        $this->assertTrue($model->getIsPlain());

        $model->setBody('e-mail body');
        $this->assertEquals('e-mail body', $model->getBody());

        $model->setIsSmtpDisabled(false);
        $this->assertFalse($model->isIsSmtpDisabled());

        $model->setIsAccepted(false);
        $this->assertFalse($model->isAccepted());
    }


    public function testSetVariables()
    {
        $model = Mage::getModel('mp_debug/email');
        $variables = array(
            'int'    => 10,
            'float'  => 10.5,
            'string' => 'mp_debug',
            'array'  => array('key1' => 1, 'key2' => 2),
            'object' => new Varien_Object(),
            'null'   => NULL
        );

        $model->setVariables($variables);
        $actual = $model->getVariables();

        $this->assertArrayHasKey('int', $actual);
        $this->assertEquals(10, $actual['int']);

        $this->assertArrayHasKey('float', $actual);
        $this->assertEquals(10.5, $actual['float']);

        $this->assertArrayHasKey('string', $actual);
        $this->assertEquals('mp_debug', $actual['string']);

        $this->assertArrayHasKey('array', $actual);
        $this->assertEquals(array('key1', 'key2'), $actual['array']);

        $this->assertArrayHasKey('object', $actual);
        $this->assertEquals('Varien_Object', $actual['object']);

        $this->assertArrayHasKey('null', $actual);
        $this->assertEquals('', $actual['null']);
    }

}
