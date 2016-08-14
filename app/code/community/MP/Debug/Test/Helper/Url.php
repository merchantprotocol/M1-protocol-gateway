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
 * Class MP_Debug_Test_Helper_Url
 *
 * @covers MP_Debug_Helper_Url
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Helper_Url extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Helper_Url */
    protected $helper;

    protected function setUp()
    {
        $this->helper = $this->getHelperMock('mp_debug/url', array('getUrl'));
    }


    public function testGetRouteStoreId()
    {
        $storeId = $this->helper->getRouteStoreId();
        $this->assertGreaterThan(0, $storeId);
    }


    public function testGetRequestListUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/search', array('token' => '12345'));
        $this->helper->getRequestListUrl(array('token' => '12345'));
    }


    public function testGetLatestRequestViewUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/latest', array('panel' => 'db'));
        $this->helper->getLatestRequestViewUrl('db');
    }


    public function testGetLatestRequestViewUrlWithDefault()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/latest', array('panel' => 'request'));
        $this->helper->getLatestRequestViewUrl();
    }


    public function testGetRequestViewUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/view', array('token' => '123', 'panel' => 'layout'));
        $this->helper->getRequestViewUrl('123', 'layout');
    }


    public function testGetRequestViewUrlWithDefault()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/view', array('token' => '123', 'panel' => 'request'));
        $this->helper->getRequestViewUrl('123');
    }


    public function testGetUrl()
    {
        $helper = $this->getHelperMock('mp_debug/url', array('getRouteStoreId', '_getUrl'));
        $helper->expects($this->any())->method('getRouteStoreId')->willReturn(10);
        $helper->expects($this->once())->method('_getUrl')
            ->with(
                'mp_debug/somepath',
                array(
                    'param1' => 1,
                    '_store' => 10,
                    '_nosid' => true
                )
            )
            ->willReturn('some url');

        $actual = $helper->getUrl('somepath', array('param1' => 1));
        $this->assertEquals('some url', $actual);
    }


    public function testGetEnableModuleUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('module/enable', array('module' => 'MP_Debug'));
        $this->helper->getEnableModuleUrl('MP_Debug');
    }


    public function testGetDisableModuleUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('module/disable', array('module' => 'MP_Debug'));
        $this->helper->getDisableModuleUrl('MP_Debug');
    }


    public function testGetEnableSqlProfilerUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('model/enableSqlProfiler');
        $this->helper->getEnableSqlProfilerUrl();
    }


    public function testGetDisableSqlProfilerUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('model/disableSqlProfiler');
        $this->helper->getDisableSqlProfilerUrl();
    }


    public function testGetSelectQueryUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('model/selectSql', array('token' => '123', 'index' => 3));
        $this->helper->getSelectQueryUrl('123', 3);
    }


    public function testGetExplainQueryUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('model/describeSql', array('token' => '123', 'index' => 3));
        $this->helper->getExplainQueryUrl('123', 3);
    }


    public function testGetViewBlockUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('block/viewBlock', array('block' => 'MP_Debug_Block_Panel'));
        $this->helper->getViewBlockUrl('MP_Debug_Block_Panel');
    }


    public function testGetViewTemplateUrl()
    {
        $encodedTemplate = strtr(base64_encode('mp_debug/toolbar.phtml'), '+/=', '-_,');

        $this->helper->expects($this->once())->method('getUrl')->with('block/viewTemplate', array('template' => $encodedTemplate));
        $this->helper->getViewTemplateUrl('mp_debug/toolbar.phtml');
    }


    public function testGetViewHandleUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('design/viewHandle', array('handle' => 'default', 'store' => 4, 'area' => 'frontend'));
        $this->helper->getViewHandleUrl('default', 4, 'frontend');
    }


    public function testGetLayoutUpdatesUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('design/layoutUpdates', array('token' => '12345'));
        $this->helper->getLayoutUpdatesUrl('12345');
    }


    public function testGetViewLogUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/viewLog', array('token' => '12345', 'log' => 'system.log'));
        $this->helper->getViewLogUrl('12345', 'system.log');
    }


    public function testGetPurgeProfilesAction()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('index/purgeProfiles');
        $this->helper->getPurgeProfilesAction();
    }


    public function testGetSearchGroupClassUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/searchGroupClass');
        $this->helper->getSearchGroupClassUrl();
    }


    public function testGetFlushCacheUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/flushCache');
        $this->helper->getFlushCacheUrl();
    }


    public function testGetEnableTemplateHintsUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/enableTemplateHints');
        $this->helper->getEnableTemplateHintsUrl();
    }


    public function testGetEnableFPCDebugUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/enableFPCDebug');
        $this->helper->getEnableFPCDebugUrl();
    }


    public function testGetDisableFPCDebugUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/disableFPCDebug');
        $this->helper->getDisableFPCDebugUrl();
    }


    public function testGetEnableTranslateUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/enableTranslate');
        $this->helper->getEnableTranslateUrl();
    }


    public function testGetDisableTranslateUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('util/disableTranslate');
        $this->helper->getDisableTranslateUrl();
    }


    public function testGetPhpInfoUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('config/phpinfo');
        $this->helper->getPhpInfoUrl();
    }


    public function testGetSearchConfigUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('config/search');
        $this->helper->getSearchConfigUrl();
    }

    public function testGetDownloadConfig()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('config/download', array('type' => 'xml'));
        $this->helper->getDownloadConfig('xml');
    }

    public function testGetEnableVarienProfilerUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('config/enableVarienProfiler');
        $this->helper->getEnableVarienProfilerUrl();
    }


    public function testGetDisableVarienProfilerUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('config/disableVarienProfiler');
        $this->helper->getDisableVarienProfilerUrl();
    }


    public function testGetEmailBodyUrl()
    {
        $this->helper->expects($this->once())->method('getUrl')->with('email/getBody', array('token' => '12345', 'index' => 5));
        $this->helper->getEmailBodyUrl('12345', 5);
    }

}
