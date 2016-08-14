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
 * Class MP_Debug_Test_Helper_Performance
 *
 * @covers MP_Debug_Helper_Performance
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Helper_Performance extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Helper_Performance */
    protected $helper;

    protected function setUp()
    {
        $this->helper = Mage::helper('mp_debug/performance');
    }


    public function testGetCategory()
    {
        $this->assertEquals('doctrine', $this->helper->getCategory('EAV'));
        $this->assertEquals('event_listener', $this->helper->getCategory('OBSERVER mp_debug'));
        $this->assertEquals('event_listener', $this->helper->getCategory('DISPATCH EVENT soemthing'));
        $this->assertEquals('template', $this->helper->getCategory('some_template.phtml'));
        $this->assertEquals('template', $this->helper->getCategory('layout/'));
        $this->assertEquals('template', $this->helper->getCategory('BLOCK something'));
    }


    public function testConvertTimers()
    {
        $requestInfo = $this->getModelMock('mp_debug/requestInfo', array('getToken', 'getTimers'));
        $requestInfo->expects($this->any())->method('getToken')->willReturn('12345');
        $requestInfo->expects($this->any())->method('getTimers')->willReturn(array(
            'mage::start'     => array('sum' => 10, 'realmem' => 1000000),
            'EAV'             => array('sum' => 20, 'realmem' => 2000000),
            'layout/generate' => array('sum' => 5, 'realmem' => 1000000),
            'core.phtml'      => array('sum' => 5, 'realmem' => 1000000),
        ));

        $data = $this->helper->convertTimers($requestInfo);
        $this->assertNotNull($data);
        $this->assertArrayHasKey('max', $data);
        $this->assertArrayHasKey('requests', $data);
        $this->assertCount(1, $data['requests']);

        $requestData = $data['requests'][0];
        $this->assertArrayHasKey('id', $requestData);
        $this->assertEquals('12345', $requestData['id']);
        $this->assertArrayHasKey('events', $requestData);

        $events = $requestData['events'];
        $this->assertCount(4, $events);

        $this->assertEquals(10000, $events[1]['starttime']);
        $this->assertEquals(30000, $events[1]['endtime']);
        $this->assertEquals(20000, $events[1]['duration']);
        $this->assertEquals('EAV', $events[1]['name']);
        $this->assertEquals('doctrine', $events[1]['category']);
    }

}
