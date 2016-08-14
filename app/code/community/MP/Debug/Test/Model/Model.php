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
 * Class MP_Debug_Test_Model_Model
 *
 * @covers MP_Debug_Model_Model
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Model extends EcomDev_PHPUnit_Test_Case
{

    public function testConstruct()
    {
        $model = Mage::getModel('mp_debug/model');
        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Model', $model);
    }


    public function testInit()
    {
        $magentoModel = $this->getModelMock('catalog/product', array('getResourceName'));
        $magentoModel->expects($this->any())->method('getResourceName')->willReturn('catalog_product');

        $model = Mage::getModel('mp_debug/model');
        $model->init($magentoModel);
        $this->assertContains('Mage_Catalog_Model_Product', $model->getClass());
        $this->assertEquals('catalog_product', $model->getResource());
        $this->assertEquals(0, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(1, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(2, $model->getCount());
    }

}
