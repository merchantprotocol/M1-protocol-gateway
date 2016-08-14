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
 * Class MP_Debug_Test_Model_Db_Profiler
 *
 * @covers MP_Debug_Model_Db_Profiler
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Db_Profiler extends EcomDev_PHPUnit_Test_Case
{
    protected $model;


    protected function setUp()
    {
        $this->model = Mage::getModel('mp_debug/db_profiler');
    }


    public function testConstruct()
    {
        $this->assertNotFalse($this->model);
        $this->assertInstanceOf('MP_Debug_Model_Db_Profiler', $this->model);
    }


    public function testReplaceProfiler()
    {
        $model = $this->getModelMock('mp_debug/db_profiler', array('setEnabled'));
        $model->expects($this->once())->method('setEnabled')->with(false);

        $standardProfiler = $this->getMock('Zend_Db_Profiler');
        $standardProfiler->expects($this->any())->method('getEnabled')->willReturn(false);

        $connection = $this->getMock('Varien_Db_Adapter_Pdo_Mysql', array('getProfiler', 'setProfiler'), array(), '', false);
        $connection->expects($this->once())->method('getProfiler')->willReturn($standardProfiler);
        $connection->expects($this->once())->method('setProfiler')->with($model);

        $coreResource = $this->getModelMock('core/resource', array('getConnection'));
        $coreResource->expects($this->once())->method('getConnection')->with('core_write')->willReturn($connection);
        $this->replaceByMock('singleton', 'core/resource', $coreResource);

        $model->replaceProfiler();

    }


    public function testGetStackTrace()
    {
        $stackTrace = $this->model->getStackTrace();

        $this->assertNotEmpty($stackTrace);
        $this->assertGreaterThan(1, count($stackTrace));
        $this->assertArrayHasKey('file', $stackTrace[0]);
        $this->assertArrayHasKey('line', $stackTrace[0]);
        $this->assertArrayHasKey('class', $stackTrace[0]);
    }


    public function testQueryEnd()
    {
        $model = $this->getModelMock('mp_debug/db_profiler', array('parentQueryEnd', 'getStackTrace'));
        $model->setCaptureStacktraces(true);
        $model->expects($this->once())->method('parentQueryEnd')->with(101)->willReturn('stored');
        $model->expects($this->once())->method('getStackTrace')->willReturn('some stack trace');

        $actual = $model->queryEnd(101);
        $this->assertEquals('stored', $actual);
    }


    public function testGetQueryModels()
    {
        $zendQuery1 = $this->getMock('Zend_Db_Profiler_Query', array('getQueryType'), array(), '', false);
        $zendQuery1->expects($this->any())->method('getQueryType')->willReturn('query type');

        $this->model->queryClone($zendQuery1);

        $queryModelMock = $this->getModelMock('mp_debug/query', array('init'));
        $this->replaceByMock('model', 'mp_debug/query', $queryModelMock);
        $queryModelMock->expects($this->at(0))->method('init')->with($this->isInstanceOf('Zend_Db_Profiler_Query'));

        $queryModels = $this->model->getQueryModels();

        $this->assertCount(1, $queryModels);
        $this->assertInstanceOf('MP_Debug_Model_Query', $queryModels[0]);
    }

}
