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
 * Class MP_Debug_Test_Block_Controller
 *
 * @covers MP_Debug_Block_Controller
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Controller extends EcomDev_PHPUnit_Test_Case
{

    public function testGetController()
    {
        $controller = $this->getModelMock('mp_debug/controller', array('getResponseCode'), false, array(), '', false);
        $requestInfo = $this->getModelMock('mp_debug/requestInfo', array('getController'));
        $requestInfo->expects($this->any())->method('getController')->willReturn($controller);

        $block = $this->getBlockMock('mp_debug/controller', array('getRequestInfo'));
        $block->expects($this->any())->method('getRequestInfo')->willReturn($requestInfo);

        $actual = $block->getController();
        $this->assertNotNull($controller);
        $this->assertEquals($controller, $actual);
    }


    public function testGetResponseCode()
    {
        $controller = $this->getModelMock('mp_debug/controller', array('getResponseCode'), false, array(), '', false);
        $controller->expects($this->once())->method('getResponseCode')->willReturn(204);

        $response = $this->getMock('Mage_Core_Controller_Response_Http', array('getHttpResponseCode'));
        $response->expects($this->any())->method('getHttpResponseCode')->willReturn(202);
        $action = $this->getMock('Mage_Core_Controller_Varien_Action', array('getResponse'), array(), '', false);
        $action->expects($this->any())->method('getResponse')->willReturn($response);

        $block = $this->getBlockMock('mp_debug/controller', array('getController', 'getAction'));
        $block->expects($this->any())->method('getController')->willReturn($controller);
        $block->expects($this->any())->method('getAction')->willReturn($action);


        $actual = $block->getResponseCode();
        $this->assertEquals(204, $actual);
    }


    public function testGetResponseCodeFromCurrentResponse()
    {
        $controller = $this->getModelMock('mp_debug/controller', array('getResponseCode'), false, array(), '', false);
        $controller->expects($this->once())->method('getResponseCode')->willReturn(null);

        $response = $this->getMock('Mage_Core_Controller_Response_Http', array('getHttpResponseCode'));
        $response->expects($this->any())->method('getHttpResponseCode')->willReturn(202);
        $action = $this->getMock('Mage_Core_Controller_Varien_Action', array('getResponse'), array(), '', false);
        $action->expects($this->any())->method('getResponse')->willReturn($response);

        $block = $this->getBlockMock('mp_debug/controller', array('getController', 'getAction'));
        $block->expects($this->any())->method('getController')->willReturn($controller);
        $block->expects($this->any())->method('getAction')->willReturn($action);


        $actual = $block->getResponseCode();
        $this->assertEquals(202, $actual);
    }


    /**
     * Expected color for response code
     *
     * @return array
     */
    public function colorForResponseCode()
    {
        return array(
            array('red', 404),
            array('red', 503),
            array('yellow', 301),
            array('green', 200),
            array('green', 201)
        );
    }


    /**
     * @dataProvider colorForResponseCode
     */
    public function testGetStatusColor($expectedColor, $responseCode)
    {
        $block = $this->getBlockMock('mp_debug/controller', array('getResponseCode'));
        $block->expects($this->any())->method('getResponseCode')->willReturn($responseCode);

        $actual = $block->getStatusColor();
        $this->assertEquals($expectedColor, $actual);
    }

}
