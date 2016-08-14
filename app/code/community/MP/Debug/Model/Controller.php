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
 * Class MP_Debug_Model_Controller captures information about the HTTP request, HTTP response and used controller.
 */
class MP_Debug_Model_Controller
{
    protected $serverParameters;

    protected $httpMethod;
    // request url path
    protected $requestOriginalPath;
    // request url path after rewrite (internal url path)
    protected $requestPath;
    protected $requestHeaders;
    protected $remoteIp;

    protected $routeName;
    protected $module;
    protected $class;
    protected $action;

    protected $sessionId;
    protected $cookies;
    protected $session;
    protected $getParameters;
    protected $postParameters;

    protected $responseCode;
    protected $responseHeaders;


    /**
     * Captures request, response and controller information.
     *
     * MP_Debug_Model_Controller constructor.
     * @param Mage_Core_Controller_Varien_Action $action
     */
    public function init($action)
    {
        $helper = Mage::helper('mp_debug');

        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $action->getRequest();

        $this->httpMethod = $request->getMethod();
        $this->requestOriginalPath = $request->getOriginalPathInfo();
        $this->requestPath = $request->getPathInfo();
        $this->remoteIp = Mage::helper('core/http')->getRemoteAddr();

        $this->routeName = $request->getRouteName();
        $this->module = $request->getControllerModule();
        $this->class = get_class($action);
        $this->action = $action->getActionMethodName($request->getActionName());
        $this->sessionId = Mage::getSingleton('core/session')->getEncryptedSessionId();

        $this->serverParameters = $helper->getGlobalServer();
        $this->requestHeaders = $helper->getAllHeaders();
        $this->cookies = $helper->getGlobalCookie();
        $this->session = $helper->getGlobalSession();
        $this->getParameters = $helper->getGlobalGet();
        $this->postParameters = $helper->getGlobalPost();
    }


    /**
     * Initialize response properties
     *
     * @param Mage_Core_Controller_Response_Http $httpResponse
     */
    public function addResponseInfo(Mage_Core_Controller_Response_Http $httpResponse)
    {
        $this->responseCode = $httpResponse->getHttpResponseCode();

        $this->responseHeaders = array();
        $headers = $httpResponse->getHeaders();
        foreach ($headers as $header) {
            $this->responseHeaders[$header['name']] = $header['value'];
        }
    }


    /**
     * Returns request path
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }


    /**
     * Returns route name
     *
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->routeName;
    }


    /**
     * Returns module name
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }


    /**
     * Returns controller class that handled the request
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Returns controller's method that handled the request
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * Returns a meaningful code reference that includes controller class and action method
     *
     * @return string
     */
    public function getReference()
    {
        return $this->getClass() . '::' . $this->getAction();
    }


    /**
     * Returns cookies state during the request
     *
     * @return mixed
     */
    public function getCookies()
    {
        return $this->cookies;
    }


    /**
     * Returns session state during the request
     *
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }


    /**
     * Request request's get parameters
     *
     * @return mixed
     */
    public function getGetParameters()
    {
        return $this->getParameters;
    }


    /**
     * Returns request's post parameters
     *
     * @return mixed
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }


    /**
     * Returns request handling attributes
     *
     * @return array
     */
    public function getRequestAttributes()
    {
        return array(
            'route'  => $this->routeName,
            'module' => $this->module,
            'action' => $this->getReference()
        );
    }


    /**
     * Returns response code
     *
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }


    /**
     * Returns session id during the request
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }


    /**
     * Returns request's http method
     *
     * @return mixed
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }


    /**
     * Returns request's original path (before Magento's rewrite)
     *
     * @return string
     */
    public function getRequestOriginalPath()
    {
        return $this->requestOriginalPath;
    }


    /**
     * Returns request's headers
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }


    /**
     * Returns server parameters during the request
     *
     * @return mixed
     */
    public function getServerParameters()
    {
        return $this->serverParameters;
    }


    /**
     * Returns response headers
     *
     * @return mixed
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }


    /**
     * Returns remote ip
     *
     * @return mixed
     */
    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

}
