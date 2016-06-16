<?php

/**
 * Class MP_Debug_Test_Block_Controller
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
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
