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
 * Class MP_Debug_Test_Block_Logging
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
