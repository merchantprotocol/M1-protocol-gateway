<?php

/**
 * Class MP_Debug_Model_Resource_RequestInfo
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Model_Resource_RequestInfo extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('mp_debug/request_info', 'id');
    }

}
