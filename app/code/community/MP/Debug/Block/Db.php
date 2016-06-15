<?php

/**
 * Class MP_Debug_Block_Models
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Block_Db extends MP_Debug_Block_Panel
{

    /**
     * Checks if SQL Profiler is enabled
     *
     * @return bool
     */
    public function isSqlProfilerEnabled()
    {
        return $this->helper->getSqlProfiler()->getEnabled();
    }

}
