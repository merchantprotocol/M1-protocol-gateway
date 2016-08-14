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
 * Class MP_Debug_Test_Block_Toolbar
 *
 * @covers MP_Debug_Block_Toolbar
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Toolbar extends EcomDev_PHPUnit_Test_Case
{

    public function testRenderView()
    {
        $helper = $this->getModelMock('mp_debug', array('canShowToolbar'));
        $helper->expects($this->any())->method('canShowToolbar')->willReturn(true);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/toolbar', array('fetchView'));
        $block->expects($this->once())->method('fetchView')->willReturn('content');

        $actual = $block->renderView();
        $this->assertEquals('content', $actual);
    }


    public function testRenderViewWithoutPermissions()
    {
        $helper = $this->getModelMock('mp_debug', array('canShowToolbar'));
        $helper->expects($this->any())->method('canShowToolbar')->willReturn(false);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/toolbar', array('fetchView'));
        $block->expects($this->never())->method('fetchView')->willReturn('content');

        $actual = $block->renderView();
        $this->assertEquals('', $actual);
    }


    public function testGetVersion()
    {
        $helper = $this->getModelMock('mp_debug', array('getModuleVersion'));
        $helper->expects($this->any())->method('getModuleVersion')->willReturn('1.2.3');
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/toolbar', array('fetchView'));

        $actual = $block->getVersion();
        $this->assertEquals('1.2.3', $actual);
    }

    public function testGetVisiblePanels()
    {
        $block = $this->getBlockMock('mp_debug/toolbar', array('getSortedChildBlocks'));
        $block->expects($this->once())->method('getSortedChildBlocks')->willReturn(array(
            $this->getBlockMock('core/template'),
            $this->getBlockMock('mp_debug/panel'),
            $this->getBlockMock('mp_debug/panel')
        ));

        $actual = $block->getVisiblePanels();
        $this->assertNotNull($actual);
        $this->assertCount(2, $actual);

        $actual = $block->getVisiblePanels();
        $this->assertCount(2, $actual);
    }
}
