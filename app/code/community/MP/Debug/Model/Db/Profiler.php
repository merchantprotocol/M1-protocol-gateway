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

class MP_Debug_Model_Db_Profiler extends Zend_Db_Profiler
{
    protected $stackTraces = array();
    protected $captureStacktraces = false;

    /**
     * Responsible to copy queries from current profiler and set this instance sql profiler
     *
     * @throws Zend_Db_Profiler_Exception
     */
    public function replaceProfiler()
    {
        /** @var Magento_Db_Adapter_Pdo_Mysql $connection */
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $currentProfile = $connection->getProfiler();

        if ($currentProfile) {
            // Copy queries
            $this->_queryProfiles = $currentProfile->_queryProfiles;
        }

        $this->setEnabled($currentProfile->getEnabled());
        $connection->setProfiler($this);
    }


    /**
     * @param $queryId
     * @return string
     * @throws Zend_Db_Profiler_Exception
     */
    public function parentQueryEnd($queryId)
    {
        return parent::queryEnd($queryId);
    }


    /**
     * Returns stack trace as array
     *
     * @return string
     */
    public function getStackTrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return array_slice($trace, 2);
    }


    /**
     * Calls parent implementation and saves stack trace
     *
     * @param int $queryId
     * @return string
     */
    public function queryEnd($queryId)
    {
        $result = $this->parentQueryEnd($queryId);

        if ($this->captureStacktraces) {
            $this->stackTraces[$queryId] = $this->getStackTrace();
        }

        return $result;
    }


    /**
     * Returns an array of SQL queries
     *
     * @return MP_Debug_Model_Query[]
     */
    public function getQueryModels()
    {
        $queries  = array();
        foreach ($this->_queryProfiles as $queryId => $queryProfile) {
            $queryModel = Mage::getModel('mp_debug/query');
            $stacktrace = array_key_exists($queryId, $this->stackTraces) ? $this->stackTraces[$queryId] : '';
            $queryModel->init($queryProfile, $stacktrace);

            $queries[] = $queryModel;
        }

        return $queries;
    }


    /**
     * @param boolean $captureStacktraces
     */
    public function setCaptureStacktraces($captureStacktraces)
    {
        $this->captureStacktraces = $captureStacktraces;
    }


    /**
     * @return boolean
     */
    public function isCaptureStacktraces()
    {
        return $this->captureStacktraces;
    }

}
