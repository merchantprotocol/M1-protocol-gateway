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
 * Class MP_Debug_Test_Model_Logging
 *
 * @covers MP_Debug_Model_Logging
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Logging extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Model_Logging */
    protected $model;

    protected function setUp()
    {
        $this->model = Mage::getModel('mp_debug/logging');
    }


    public function testAddFiles()
    {
        $this->model->addFile('system.log');
        $this->model->addFile('mp_debug.log');

        $files = $this->model->getFiles();
        $this->assertCount(2, $files);
        $this->assertContains('system.log', $files);
        $this->assertContains('mp_debug.log', $files);
    }


    public function testAddRange()
    {
        $this->model->addRange('mp_debug.log', 10, 200);
        $this->model->addRange('system.log', 100, 120);

        $actual = $this->model->getRange('mp_debug.log');
        $this->assertArrayHasKey('start', $actual);
        $this->assertEquals(10, $actual['start']);
        $this->assertArrayHasKey('end', $actual);
        $this->assertEquals(200, $actual['end']);
    }


    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid log file
     */
    public function testAddRangeInvalid()
    {
        $this->model->addRange('mp_debug.log', 10, 200);
        $this->model->getRange('system.log');
    }


    public function testGetLogFilePath()
    {
        $actual = $this->model->getLogFilePath('mp_debug.log');
        $this->assertContains('var/log/mp_debug.log', $actual);
    }


    public function testStartRequest()
    {
        /** @var MP_Debug_Model_Logging $model */
        $model = $this->getModelMock('mp_debug/logging', array('getLogFilePath', 'getLastFilePosition'));
        $model->expects($this->at(0))->method('getLogFilePath')->with('system.log')->willReturn('var/log/system.log');
        $model->expects($this->at(1))->method('getLastFilePosition')->with('var/log/system.log')->willReturn(100);
        $model->expects($this->at(2))->method('getLogFilePath')->with('mp_debug.log')->willReturn('var/log/mp_debug.log');
        $model->expects($this->at(3))->method('getLastFilePosition')->with('var/log/mp_debug.log')->willReturn(200);

        $model->addFile('system.log');
        $model->addFile('mp_debug.log');

        $model->startRequest();

        $range = $model->getRange('system.log');
        $this->assertEquals(100, $range['start']);
        $range = $model->getRange('mp_debug.log');
        $this->assertEquals(200, $range['start']);
    }

    public function testEndRequest()
    {
        /** @var MP_Debug_Model_Logging $model */
        $model = $this->getModelMock('mp_debug/logging', array('getLogFilePath', 'getLastFilePosition'));
        $model->expects($this->at(0))->method('getLogFilePath')->with('system.log')->willReturn('var/log/system.log');
        $model->expects($this->at(1))->method('getLastFilePosition')->with('var/log/system.log')->willReturn(300);
        $model->expects($this->at(2))->method('getLogFilePath')->with('mp_debug.log')->willReturn('var/log/mp_debug.log');
        $model->expects($this->at(3))->method('getLastFilePosition')->with('var/log/mp_debug.log')->willReturn(250);

        $model->addFile('system.log');
        $model->addFile('mp_debug.log');

        $model->endRequest();

        $range = $model->getRange('system.log');
        $this->assertEquals(300, $range['end']);
        $range = $model->getRange('mp_debug.log');
        $this->assertEquals(250, $range['end']);
    }


    public function testGetLogging()
    {
        /** @var MP_Debug_Model_Logging $model */
        $model = $this->getModelMock('mp_debug/logging', array('getLoggedContent'));
        $model->expects($this->at(0))->method('getLoggedContent')->with('system.log')->willReturn("line 1\nline 2");
        $model->expects($this->at(1))->method('getLoggedContent')->with('mp_debug.log')->willReturn("debug1\ndebug2\ndebug3");

        $model->addFile('system.log');
        $model->addFile('mp_debug.log');

        $actual = $model->getLogging();
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('system.log', $actual);
        $this->assertEquals("line 1\nline 2", $actual['system.log']);
        $this->assertArrayHasKey('mp_debug.log', $actual);
        $this->assertEquals("debug1\ndebug2\ndebug3", $actual['mp_debug.log']);
    }


    public function testGetLoggedContent()
    {
        /** @var MP_Debug_Model_Logging $model */
        $model = $this->getModelMock('mp_debug/logging', array('getContent', 'getLogFilePath'));
        $model->expects($this->once())->method('getLogFilePath')->with('mp_debug.log')->willReturn('fp_mp_debug.log');
        $model->expects($this->once())->method('getContent')->with('fp_mp_debug.log', 300, 350)->willReturn('sheep debug content');

        $model->addFile('system.log');
        $model->addRange('system.log', 100, 250);
        $model->addFile('mp_debug.log');
        $model->addRange('mp_debug.log', 300, 350);

        $actual = $model->getLoggedContent('mp_debug.log');
        $this->assertEquals('sheep debug content', $actual);
    }


    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid log file
     */
    public function testGetLoggedContentInvalid()
    {

        /** @var MP_Debug_Model_Logging $model */
        $model = $this->getModelMock('mp_debug/logging', array('getContent'));

        $model->addFile('system.log');
        $model->addRange('system.log', 100, 250);
        $model->addFile('mp_debug.log');
        $model->addRange('mp_debug.log', 300, 350);

        $model->getLoggedContent('mp_debug_not_found.log');
    }


    public function testGetLineCount()
    {
        $model = $this->getModelMock('mp_debug/logging', array('getLoggedContent'));
        $model->expects($this->once())->method('getLoggedContent')->with('mp_debug.log')->willReturn("debug1\ndebug2\ndebug3\n");

        $actual = $model->getLineCount('mp_debug.log');
        $this->assertEquals(3, $actual);
    }


    public function testGetTotalLineCount()
    {
        $model = $this->getModelMock('mp_debug/logging', array('getFiles', 'getLineCount'));
        $model->expects($this->once())->method('getFiles')->willReturn(array('system.log', 'mp_debug.log'));
        $model->expects($this->at(1))->method('getLineCount')->with('system.log')->willReturn(2);
        $model->expects($this->at(2))->method('getLineCount')->with('mp_debug.log')->willReturn(5);

        $actual = $model->getTotalLineCount();
        $this->assertEquals(7, $actual);

        // Test result is cached
        $actual = $model->getTotalLineCount();
        $this->assertEquals(7, $actual);
    }

}
