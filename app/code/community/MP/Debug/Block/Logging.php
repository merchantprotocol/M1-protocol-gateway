<?php

/**
 * Class MP_Debug_Block_Logging
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Block_Logging extends MP_Debug_Block_Panel
{
    protected $logLineCount = null;

    /**
     * @return MP_Debug_Model_Logging
     */
    public function getLogging()
    {
        return $this->getRequestInfo()->getLogging();
    }


    /**
     * Returns an array with all registered log file names
     *
     * @return array
     */
    public function getLogFiles()
    {
        return $this->getLogging()->getFiles();
    }

}
