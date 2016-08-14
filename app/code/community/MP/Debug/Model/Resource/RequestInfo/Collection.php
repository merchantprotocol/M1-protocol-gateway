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
 * Class MP_Debug_Model_Resource_RequestInfo_Collection
 */
class MP_Debug_Model_Resource_RequestInfo_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('mp_debug/requestInfo');
    }


    /**
     * Filters request profiles by session id.
     * We capture encrypted session id. @see \MP_Debug_Model_Controller::init
     *
     * @param string $sessionId
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addSessionIdFilter($sessionId)
    {
        return $this->addFieldToFilter('session_id', $sessionId);
    }


    /**
     * Filters request profiles by specified token.
     *
     * @param string $token
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addTokenFilter($token)
    {
        return $this->addFieldToFilter('token', $token);
    }


    /**
     * Filters request profiles by HTTP method
     *
     * @param string $method
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addHttpMethodFilter($method)
    {
        return $this->addFieldToFilter('http_method', $method);
    }


    /**
     * Filters request profiles with request path containing specified value
     *
     * @param string $requestPath
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addRequestPathFilter($requestPath)
    {
        return $this->addFieldToFilter('request_path', array('like' => '%' . $requestPath . '%'));
    }


    /**
     * Filters requests profile that had specified response code
     *
     * @param int $responseCode
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addResponseCodeFilter($responseCode)
    {
        return $this->addFieldToFilter('response_code', (int)$responseCode);
    }


    /**
     * Filters requests profile that had requests initiated for specified ip
     *
     * @param string $ip
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addIpFilter($ip)
    {
        return $this->addFieldToFilter('ip', $ip);
    }


    /**
     * Filters requests that were processed before specified date
     *
     * @param string $date Date string using format Y-m-d H
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addEarlierFilter($date)
    {
        return $this->addFieldToFilter('date', array(
            'to'       => $date,
            'datetime' => true,
        ));
    }


    /**
     * Filters requests that were processed after specified date
     *
     * @param string $date
     * @return MP_Debug_Model_Resource_RequestInfo_Collection
     */
    public function addAfterFilter($date)
    {
        return $this->addFieldToFilter('date', array(
            'from' => $date,
            'datetime' => true
        ));
    }

}
