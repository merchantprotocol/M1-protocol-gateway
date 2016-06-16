<?php

/**
 * Class MP_Debug_Test_Block_Logging
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Block_Logging
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Logging extends EcomDev_PHPUnit_Test_Case
{

    public function testGetLogging()
    {
        $logging = $this->getModelMock('mp_debug/logging', array('getFiles'), false, array(), '', false);
        $requestInfo = $this->getModelMock('mp_debug/requestInfo', array('getLogging'));
        $requestInfo->expects($this->any())->method('getLogging')->willReturn($logging);

        $block = $this->getBlockMock('mp_debug/logging', array('getRequestInfo'));
        $block->expects($this->any())->method('getRequestInfo')->willReturn($requestInfo);

        $actual = $block->getLogging();
        $this->assertNotNull($logging);
        $this->assertEquals($logging, $actual);
    }


    public function testLogFiles()
    {
        $logging = $this->getModelMock('mp_debug/logging', array('getFiles'), false, array(), '', false);
        $logging->expects($this->once())->method('getFiles')->willReturn(array('a', 'b'));

        $block = $this->getBlockMock('mp_debug/logging', array('getLogging'));
        $block->expects($this->any())->method('getLogging')->willReturn($logging);

        $actual = $block->getLogFiles();
        $this->assertNotNull($actual);
        $this->assertCount(2, $actual);
        $this->assertEquals(array('a', 'b'), $actual);
    }

}
