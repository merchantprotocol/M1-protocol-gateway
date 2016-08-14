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
 * Class MP_Debug_Block_Controller
 */
class MP_Debug_Block_Controller extends MP_Debug_Block_Panel
{

    /**
     * @return MP_Debug_Model_Controller
     */
    public function getController()
    {
        return $this->getRequestInfo()->getController();
    }

    /**
     * Returns response code from request profile or from current response
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->getController()->getResponseCode() ?: $this->getAction()->getResponse()->getHttpResponseCode();
    }

    /**
     * Returns status color prefix for CSS based on response status code
     *
     * @return string
     */
    public function getStatusColor()
    {
        $responseCode = $this->getResponseCode();

        return $responseCode > 399 ? 'red' : ( $responseCode > 299 ? 'yellow' :  'green');
    }

}
