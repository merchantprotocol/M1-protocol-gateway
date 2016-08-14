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
 * Class MP_Debug_Test_Block_Abstract
 *
 * @covers MP_Debug_Block_Abstract
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Abstract extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Block_Abstract */
    protected $block;

    protected function setUp()
    {
        $this->block = $this->getBlockMock('mp_debug/abstract', array('_toHtml'));
    }


    public function testGetCacheLifetime()
    {
        $this->assertNull($this->block->getCacheLifetime());
    }


    public function testGetDefaultStoreId()
    {
        $actual = $this->block->getDefaultStoreId();
        $this->assertGreaterThanOrEqual(0, $actual);
    }


    public function testGetShowTemplateHints()
    {
        $this->assertFalse($this->block->getShowTemplateHints());
    }


    public function testParentTranslate()
    {
        $this->assertEquals('simple text', $this->block->parentTranslate(array('simple text')));
        $this->assertEquals('simple text 10 20.9', $this->block->parentTranslate(array('simple text %d %0.1f', 10, 20.90)));
    }


    public function testDummyTranslate()
    {
        $this->assertEquals('simple text', $this->block->dummyTranslate(array('simple text')));
        $this->assertEquals('simple text 10 20.9', $this->block->dummyTranslate(array('simple text %d %0.1f', 10, 20.90)));
    }


    public function testTranslate()
    {
        $helper = $this->getHelperMock('mp_debug', array('useStoreLocale'));
        $helper->expects($this->once())->method('useStoreLocale')->willReturn(false);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/abstract', array('parentTranslate', 'dummyTranslate'));
        $block->expects($this->never())->method('parentTranslate');
        $block->expects($this->once())->method('dummyTranslate')->with(array('some text', 10, 20))->willReturn('translated text');;

        $actual = $block->__('some text', 10, 20);
        $this->assertEquals('translated text', $actual);
    }


    public function testTranslateWithStoreLocale()
    {
        $helper = $this->getHelperMock('mp_debug', array('useStoreLocale'));
        $helper->expects($this->once())->method('useStoreLocale')->willReturn(true);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/abstract', array('parentTranslate', 'dummyTranslate'));
        $block->expects($this->once())->method('parentTranslate')->with(array('some text', 10, 20))->willReturn('translated text');
        $block->expects($this->never())->method('dummyTranslate');

        $actual = $block->__('some text', 10, 20);
        $this->assertEquals('translated text', $actual);
    }


    public function testGetRequestInfo()
    {
        $requestInfoMock = $this->getModelMock('mp_debug/requestInfo');

        $observerMock = $this->getModelMock('mp_debug/observer', array('getRequestInfo'));
        $this->replaceByMock('singleton', 'mp_debug/observer', $observerMock);
        $observerMock->expects($this->once())->method('getRequestInfo')->willReturn($requestInfoMock);

        $actual = $this->block->getRequestInfo();
        $this->assertNotNull($actual);
        $this->assertEquals($requestInfoMock, $actual);

        // test observer getRequestInfo is called only once
        $this->assertEquals($requestInfoMock, $this->block->getRequestInfo());
    }


    public function testGetRequestListUrl()
    {
        $urlHelper = $this->getHelperMock('mp_debug/url');
        $this->replaceByMock('helper', 'mp_debug/url', $urlHelper);
        $urlHelper->expects($this->once())->method('getRequestListUrl')
            ->with(array('filter1' => 'value1', 'filter2' => 'value2'))
            ->willReturn('request list url');

        $actual = $this->block->getRequestListUrl(array('filter1' => 'value1', 'filter2' => 'value2'));
        $this->assertEquals('request list url', $actual);
    }


    public function testGetLatestRequestViewUrl()
    {
        $urlHelper = $this->getHelperMock('mp_debug/url');
        $this->replaceByMock('helper', 'mp_debug/url', $urlHelper);
        $urlHelper->expects($this->once())->method('getLatestRequestViewUrl')
            ->with('logging')
            ->willReturn('latest request view url');

        $actual = $this->block->getLatestRequestViewUrl('logging');
        $this->assertEquals('latest request view url', $actual);
    }


    public function testGetRequestViewUrlWithToken()
    {
        $urlHelper = $this->getHelperMock('mp_debug/url');
        $this->replaceByMock('helper', 'mp_debug/url', $urlHelper);
        $urlHelper->expects($this->once())->method('getRequestViewUrl')
            ->with('12345', 'db')
            ->willReturn('request view url');

        $actual = $this->block->getRequestViewUrl('db', '12345');
        $this->assertEquals('request view url', $actual);
    }


    public function testGetRequestViewUrlWithoutToken()
    {
        $requestInfo = $this->getModelMock('mp_debug/requestInfo', array('getToken'));
        $requestInfo->expects($this->any())->method('getToken')->willReturn('abcdef');

        $block = $this->getBlockMock('mp_debug/abstract', array('getRequestInfo'));
        $block->expects($this->once())->method('getRequestInfo')->willReturn($requestInfo);

        $urlHelper = $this->getHelperMock('mp_debug/url');
        $this->replaceByMock('helper', 'mp_debug/url', $urlHelper);
        $urlHelper->expects($this->once())->method('getRequestViewUrl')
            ->with('abcdef', 'layout')
            ->willReturn('request view url');

        $actual = $block->getRequestViewUrl('layout');
        $this->assertEquals('request view url', $actual);
    }


    public function testFormatNumber()
    {
        $helper = $this->getHelperMock('mp_debug', array('useStoreLocale', 'formatNumber'));
        $helper->expects($this->once())->method('useStoreLocale')->willReturn(false);
        $helper->expects($this->never())->method('formatNumber');
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/abstract', array('_toHtml'));
        $actual = $block->formatNumber(10.3333);
        $this->assertEquals(10.33, $actual);
    }


    public function testFormatNumberWithStoreLocale()
    {
        $helper = $this->getHelperMock('mp_debug', array('useStoreLocale', 'formatNumber'));
        $helper->expects($this->once())->method('useStoreLocale')->willReturn(true);
        $helper->expects($this->once())->method('formatNumber')->with(10.3333, 2)->willReturn(10.33);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/abstract', array('_toHtml'));
        $actual = $block->formatNumber(10.3333);
        $this->assertEquals(10.33, $actual);
    }


    public function testGetOptionArray()
    {
        $actual = $this->block->getOptionArray(array('value1', 'value2'));

        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('value', $actual[1]);
        $this->assertEquals('value2', $actual[1]['value']);
        $this->assertArrayHasKey('label', $actual[1]);
        $this->assertEquals('value2', $actual[1]['label']);
    }


    public function statusCodeProvider()
    {
        return array(
            array(200, 'status-success'),
            array(201, 'status-success'),
            array(300, 'status-warning'),
            array(399, 'status-warning'),
            array(400, 'status-error'),
            array(503, 'status-error')
        );
    }


    /**
     * @dataProvider statusCodeProvider
     */
    public function testGetStatusCodeClass($statusCode, $expectedStatusClass)
    {
        $actual = $this->block->getStatusCodeClass($statusCode);
        $this->assertEquals($expectedStatusClass, $actual);
    }

}
