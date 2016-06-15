<?php

/**
 * Class MP_Debug_Test_Block_Db
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Block_Db
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Db extends EcomDev_PHPUnit_Test_Case
{

    public function testIsSqlProfilerEnabled()
    {
        $profiler = $this->getMock('Zend_Db_Profiler', array('getEnabled'));
        $profiler->expects($this->once())->method('getEnabled')->willReturn(true);

        $helper = $this->getHelperMock('mp_debug', array('getSqlProfiler'));
        $helper->expects($this->once())->method('getSqlProfiler')->willReturn($profiler);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/db', array('toHtml'));
        $this->assertTrue($block->isSqlProfilerEnabled());
    }

}
