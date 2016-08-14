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
 * Class MP_Debug_Model_Test_Collection
 *
 * @covers MP_Debug_Model_Collection
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Collection extends EcomDev_PHPUnit_Test_Case
{

    public function testConstruct()
    {
        $model = Mage::getModel('mp_debug/collection');
        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Collection', $model);
    }


    public function testInit()
    {
        $collection = $this->getMock('Varien_Data_Collection_Db', array('getSelectSql'));
        $collection->expects($this->once())->method('getSelectSql')->with(true)->willReturn('sql query');

        $model = Mage::getModel('mp_debug/collection');
        $model->init($collection);

        $this->assertContains('Varien_Data_Collection_Db', $model->getClass());
        $this->assertEquals('flat', $model->getType());
        $this->assertEquals('sql query', $model->getQuery());
        $this->assertEquals(0, $model->getCount());
    }


    public function testIncrementCount()
    {
        $model = Mage::getModel('mp_debug/collection');
        $this->assertEquals(0, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(1, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(2, $model->getCount());
    }

}
