<?php

/**
 * Class MP_Debug_Test_Model_Design
 *
 * @category MP
 * @package  MP_Subscription
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Model_Design
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Design extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Model_Design */
    protected $model;

    protected function setUp()
    {
        $this->model = Mage::getModel('mp_debug/design');
    }


    public function testInit()
    {
        $layoutUpdate = $this->getModelMock('core/layout_update', array('getHandles', 'asArray'));
        $layoutUpdate->expects($this->any())->method('getHandles')->willReturn(array('default', 'STORE_default', 'mp_debug'));
        $layoutUpdate->expects($this->any())->method('asArray')->willReturn(array(
            'update 1',
            'update 2'
        ));

        $layout = $this->getModelMock('core/layout', array('getUpdate'));
        $layout->expects($this->any())->method('getUpdate')->willReturn($layoutUpdate);

        $designPackage = $this->getModelMock('core/design_package',
            array('getArea', 'getPackageName', 'getTheme', 'get'));
        $designPackage->expects($this->any())->method('getArea')->willReturn('adminhtml');
        $designPackage->expects($this->any())->method('getPackageName')->willReturn('acme');
        $designPackage->expects($this->at(2))->method('getTheme')->with('layout')->willReturn('new_layout');
        $designPackage->expects($this->at(3))->method('getTheme')->with('locale')->willReturn('new_locale');
        $designPackage->expects($this->at(4))->method('getTheme')->with('template')->willReturn('new_templates');
        $designPackage->expects($this->at(5))->method('getTheme')->with('skin')->willReturn('new_skin');


        $this->model->init($layout, $designPackage);

        $this->assertEquals('adminhtml', $this->model->getArea());
        $this->assertEquals('acme', $this->model->getPackageName());
        $this->assertEquals('new_layout', $this->model->getThemeLayout());
        $this->assertEquals('new_locale', $this->model->getThemeLocale());
        $this->assertEquals('new_templates', $this->model->getThemeTemplate());
        $this->assertEquals('new_skin', $this->model->getThemeSkin());

        $this->assertCount(3, $this->model->getLayoutHandles());
        $this->assertContains('mp_debug', $this->model->getLayoutHandles());

        $this->assertCount(2, $this->model->getLayoutUpdates());
        $this->assertEquals(array('update 1', 'update 2'), $this->model->getLayoutUpdates());

        $info = $this->model->getInfoAsArray();
        $this->assertArrayHasKey('design_area', $info);
        $this->assertArrayHasKey('package_name', $info);
        $this->assertArrayHasKey('layout_theme', $info);
        $this->assertArrayHasKey('template_theme', $info);
        $this->assertArrayHasKey('locale', $info);
        $this->assertArrayHasKey('skin', $info);
    }

}
