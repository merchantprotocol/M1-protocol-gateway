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
 * Class MP_Debug_Test_Block_Util
 *
 * @covers MP_Debug_Block_Util
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Util extends EcomDev_PHPUnit_Test_Case
{

    public function testFlushCacheUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getFlushCacheUrl'));
        $helper->expects($this->once())->method('getFlushCacheUrl')->willReturn('cache flush url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getFlushCacheUrl();
        $this->assertEquals('cache flush url', $actual);
    }


    /**
     * We check current setting and assume that was not changed. getShowTemplateHints() is always
     * false for our custom blocks.
     */
    public function testIsTemplateHintsEnabled()
    {
        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $this->assertFalse($block->isTemplateHintsEnabled());
    }


    public function testGetDisableTemplateHintsUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getDisableTemplateHintsUrl'));
        $helper->expects($this->once())->method('getDisableTemplateHintsUrl')->willReturn('disable template hints url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getDisableTemplateHintsUrl();
        $this->assertEquals('disable template hints url', $actual);
    }


    public function testHasFullPageCache()
    {
        $helper = $this->getHelperMock('mp_debug', array('isMagentoEE'));
        $helper->expects($this->once())->method('isMagentoEE')->willReturn(true);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->hasFullPageCache();
        $this->assertTrue($actual);
    }


    /**
     * Assumes config is off
     */
    public function testIsFPCDebugEnabled()
    {
        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $this->assertFalse($block->isFPCDebugEnabled());
    }


    public function testGetEnableFPCDebugUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getEnableFPCDebugUrl'));
        $helper->expects($this->once())->method('getEnableFPCDebugUrl')->willReturn('enable fpc debug url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getEnableFPCDebugUrl();
        $this->assertEquals('enable fpc debug url', $actual);
    }

    public function testGetDisableFPCDebugUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getDisableFPCDebugUrl'));
        $helper->expects($this->once())->method('getDisableFPCDebugUrl')->willReturn('disable fpc debug url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getDisableFPCDebugUrl();
        $this->assertEquals('disable fpc debug url', $actual);
    }


    /**
     * Assumes config is off
     */
    public function testIsTranslateInlineEnable()
    {
        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $this->assertFalse($block->isTranslateInlineEnable());
    }


    public function testGetEnableTranslateUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getEnableTranslateUrl'));
        $helper->expects($this->once())->method('getEnableTranslateUrl')->willReturn('enable translate url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getEnableTranslateUrl();
        $this->assertEquals('enable translate url', $actual);
    }


    public function testGetDisableTranslateUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getDisableTranslateUrl'));
        $helper->expects($this->once())->method('getDisableTranslateUrl')->willReturn('disable translate url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getDisableTranslateUrl();
        $this->assertEquals('disable translate url', $actual);
    }


    public function testIsSqlProfilerEnabled()
    {
        $profiler = $this->getMock('Zend_Db_Profiler', array('getEnabled'));
        $profiler->expects($this->once())->method('getEnabled')->willReturn(true);

        $helper = $this->getHelperMock('mp_debug', array('getSqlProfiler'));
        $helper->expects($this->once())->method('getSqlProfiler')->willReturn($profiler);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $this->assertTrue($block->isSqlProfilerEnabled());
    }


    /**
     * Assume is off
     */
    public function testIsVarienProfilerEnabled()
    {
        $helper = $this->getHelperMock('mp_debug', array('canEnableVarienProfiler'));
        $helper->expects($this->any())->method('canEnableVarienProfiler')->willReturn(false);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $this->assertFalse($block->isVarienProfilerEnabled());
    }


    public function testGetEnableSqlProfilerUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getEnableSqlProfilerUrl'));
        $helper->expects($this->once())->method('getEnableSqlProfilerUrl')->willReturn('enable sql profiler url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getEnableSqlProfilerUrl();
        $this->assertEquals('enable sql profiler url', $actual);
    }


    public function testGetDisableSqlProfilerUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getDisableSqlProfilerUrl'));
        $helper->expects($this->once())->method('getDisableSqlProfilerUrl')->willReturn('disable sql profiler url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getDisableSqlProfilerUrl();
        $this->assertEquals('disable sql profiler url', $actual);
    }


    public function testGetEnableVarienProfilerUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getEnableVarienProfilerUrl'));
        $helper->expects($this->once())->method('getEnableVarienProfilerUrl')->willReturn('enable varien profiler url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getEnableVarienProfilerUrl();
        $this->assertEquals('enable varien profiler url', $actual);
    }

    public function testGetDisableVarienProfilerUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getDisableVarienProfilerUrl'));
        $helper->expects($this->once())->method('getDisableVarienProfilerUrl')->willReturn('disable varien profiler url');
        $this->replaceByMock('helper', 'mp_debug/url', $helper);

        $block = $this->getBlockMock('mp_debug/util', array('toHtml'));
        $actual = $block->getDisableVarienProfilerUrl();
        $this->assertEquals('disable varien profiler url', $actual);
    }

}

