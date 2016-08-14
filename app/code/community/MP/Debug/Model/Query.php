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
 * Class MP_Debug_Model_Query
 */
class MP_Debug_Model_Query
{
    protected $queryType;
    protected $query;
    protected $queryParams;
    protected $elapsedSecs;
    protected $stacktrace;


    /**
     * MP_Debug_Model_Query constructor.
     *
     * @param Zend_Db_Profiler_Query $profilerQuery
     * @param string                 $stacktrace
     */
    public function init(Zend_Db_Profiler_Query $profilerQuery, $stacktrace = '')
    {
        $this->queryType = $profilerQuery->getQueryType();
        $this->query = $profilerQuery->getQuery();
        $this->queryParams = $profilerQuery->getQueryParams();
        $this->elapsedSecs = $profilerQuery->getElapsedSecs();
        $this->stacktrace = $stacktrace;
    }


    /**
     * Returns query type
     *
     * @return int
     */
    public function getQueryType()
    {
        return $this->queryType;
    }


    /**
     * Returns SQL query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * Returns SQL query parameters
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }


    /**
     * Returns execution time in seconds
     *
     * @return false|float
     */
    public function getElapsedSecs()
    {
        return $this->elapsedSecs;
    }

    /**
     * @return string
     */
    public function getStacktrace()
    {
        return $this->stacktrace;
    }

}
