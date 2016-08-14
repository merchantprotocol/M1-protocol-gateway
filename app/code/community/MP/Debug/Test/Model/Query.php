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
 * Class MP_Debug_Test_Model_Query
 *
 * @covers MP_Debug_Model_Query
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Query extends EcomDev_PHPUnit_Test_Case
{

    public function test()
    {
        $zendQuery = $this->getMock('Zend_Db_Profiler_Query',
            array('getQueryType', 'getQuery', 'getQueryParams', 'getElapsedSecs'), array(), '', false);
        $zendQuery->expects($this->any())->method('getQueryType')->willReturn(Zend_Db_Profiler::SELECT);
        $zendQuery->expects($this->any())->method('getQuery')->willReturn('raw query');
        $zendQuery->expects($this->any())->method('getQueryParams')->willReturn(array(
            ':store_id' => 10,
            ':customer_id' => 5
        ));
        $zendQuery->expects($this->any())->method('getElapsedSecs')->willReturn(0.123);

        $model = Mage::getModel('mp_debug/query');
        $model->init($zendQuery, 'stack trace');

        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Query', $model);
        $this->assertEquals(32, $model->getQueryType());
        $this->assertEquals('raw query', $model->getQuery());
        $this->assertCount(2, $model->getQueryParams());
        $this->assertEquals(5, $model->getQueryParams()[':customer_id']);
        $this->assertEquals(0.123, $model->getElapsedSecs());
        $this->assertEquals('stack trace', $model->getStacktrace());
    }

}
