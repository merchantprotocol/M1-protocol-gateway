<?php

/**
 * Class MP_Debug_Test_Helper_Filter
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Helper_Filter
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Helper_Filter extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Helper_Filter */
    protected $helper;

    protected function setUp()
    {
        $this->helper = Mage::helper('mp_debug/filter');
    }


    public function testGetFilterParams()
    {
        $params = $this->helper->getFilterParams();
        $this->assertNotNull($params);
        $this->assertContains('ip', $params);
        $this->assertContains('token', $params);
        $this->assertContains('limit', $params);
    }


    public function testGetRequestFilters()
    {
        $filterParams = array('token', 'limit', 'invalid_filter');
        $helper = $this->getHelperMock('mp_debug/filter', array('getFilterParams'));
        $helper->expects($this->any())->method('getFilterParams')->willReturn($filterParams);

        $request = $this->getMock('Mage_Core_Controller_Request_Http', array('getParam'), array(), '', false);
        $request->expects($this->at(0))->method('getParam')->with('token', null)->willReturn('12345');
        $request->expects($this->at(1))->method('getParam')->with('limit', null)->willReturn(55);

        $filterValues = $helper->getRequestFilters($request);

        $this->assertNotNull($filterValues);
        $this->assertCount(2, $filterValues);
        $this->assertArrayHasKey('token', $filterValues);
        $this->assertEquals('12345', $filterValues['token']);
        $this->assertArrayHasKey('limit', $filterValues);
        $this->assertEquals(55, $filterValues['limit']);
        $this->assertArrayNotHasKey('invalid_filter', $filterValues);
    }


    public function testGetHttpMethodValues()
    {
        $methods = $this->helper->getHttpMethodValues();
        $this->assertNotNull($methods);
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
    }


    public function testGetLimitDefaultValue()
    {
        $this->assertEquals(10, $this->helper->getLimitDefaultValue());
    }


    public function testGetLimitValues()
    {
        $this->assertEquals(array(10, 50, 100), $this->helper->getLimitValues());
    }

}
