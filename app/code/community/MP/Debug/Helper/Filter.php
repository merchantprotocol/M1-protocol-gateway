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
 * Class MP_Debug_Helper_Filter
 */
class MP_Debug_Helper_Filter extends Mage_Core_Helper_Abstract
{
    const DEFAULT_LIMIT_VALUE = 10;

    protected $requestFilterValues;

    /**
     * Returns available filter names
     */
    public function getFilterParams()
    {
        return array('ip', 'method', 'path', 'token', 'start', 'limit', 'session_id');
    }


    /**
     * Returns an assoc array with filter and if its value from request.
     * Filters missing from request parameters are ignored.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @return array
     */
    public function getRequestFilters(Mage_Core_Controller_Request_Http $request)
    {
        if (!$this->requestFilterValues) {
            $filters = $this->getFilterParams();
            $this->requestFilterValues = array();

            foreach ($filters as $filter) {
                $param = $request->getParam($filter, null);
                if ($param !== null) {
                    $this->requestFilterValues[$filter] = $param;
                }
            }
        }

        return $this->requestFilterValues;
    }

    /**
     * Returns accepted values for http method filter
     *
     * @return array
     */
    public function getHttpMethodValues()
    {
        return array(
            'DELETE', 'GET', 'HEAD', 'PATCH', 'POST', 'PUT'
        );
    }


    /**
     * Returns default value for limit filter
     *
     * @return int
     */
    public function getLimitDefaultValue()
    {
        return self::DEFAULT_LIMIT_VALUE;
    }


    /**
     * Returns available values for limit filter
     *
     * @return array
     */
    public function getLimitValues()
    {
        return array(10, 50, 100);
    }

}
