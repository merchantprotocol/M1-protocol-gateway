<?php

/**
 * Class MP_Debug_Model_Test_Collection
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
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
