<?php

/**
 * Class MP_Debug_Block_Abstract
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 */
class MP_Debug_Block_Abstract extends Mage_Core_Block_Template
{
    /** @var MP_Debug_Helper_Data */
    protected $helper;

    /** @var  MP_Debug_Model_RequestInfo */
    protected $requestInfo;


    /**
     * MP_Debug_Block_Abstract constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->helper = Mage::helper('mp_debug');
    }

    /**
     * By default we don't cache our blocks
     *
     * @return null
     */
    public function getCacheLifetime()
    {
        return null;
    }

    /**
     * Retrieve application instance
     *
     * Method added to support Magento CE 1.7
     *
     * @return Mage_Core_Model_App
     */
    protected function _getApp()
    {
        return Mage::app();
    }


    /**
     * Returns id for default configured store
     *
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function getDefaultStoreId()
    {
        return Mage::app()
            ->getWebsite()
            ->getDefaultGroup()
            ->getDefaultStoreId();
    }


    /**
     * Disable template hints for our own blocks
     *
     * @return bool
     */
    public function getShowTemplateHints()
    {
        return false;
    }


    /**
     * @param array $args
     * @return mixed
     */
    public function parentTranslate(array $args)
    {
        return call_user_func_array(array('parent', '__'), $args);
    }


    /**
     * @param array $args
     * @return mixed
     */
    public function dummyTranslate(array $args)
    {
        $text = array_shift($args);
        $result = @vsprintf($text, $args);
        return $result ?: $text;
    }


    /**
     * Don't use translation for our debug module
     *
     * @return mixed
     */
    public function __()
    {
        $args = func_get_args();
        return $this->helper->useStoreLocale() ? $this->parentTranslate($args) : $this->dummyTranslate($args);
    }


    /**
     * Returns registered request info or request profile for current request
     *
     * @return MP_Debug_Model_RequestInfo
     */
    public function getRequestInfo()
    {
        if ($this->requestInfo === null) {
            $this->requestInfo = Mage::registry('mp_debug_request_info') ?: Mage::getSingleton('mp_debug/observer')->getRequestInfo();
        }

        return $this->requestInfo;
    }


    /**
     * Returns url to request profile list
     *
     * @param array $filters
     * @return string
     */
    public function getRequestListUrl(array $filters = array())
    {
        return Mage::helper('mp_debug/url')->getRequestListUrl($filters);
    }


    /**
     * Returns url to latest request profile page view
     *
     * @param string $panel
     * @return string
     */
    public function getLatestRequestViewUrl($panel = null)
    {
        return Mage::helper('mp_debug/url')->getLatestRequestViewUrl($panel);
    }


    /**
     * Returns url to specified request profile page
     *
     * @param string $panel
     * @param string $token
     * @return string
     */
    public function getRequestViewUrl($panel = null, $token = null)
    {
        $token = $token ?: $this->getRequestInfo()->getToken();
        return Mage::helper('mp_debug/url')->getRequestViewUrl($token, $panel);
    }


    /**
     * Returns number formatted based on current locale
     *
     * @param mixed $number
     * @param int   $precision
     * @return string
     */
    public function formatNumber($number, $precision = 2)
    {
        return $this->helper->useStoreLocale() ? $this->helper->formatNumber($number, $precision) : number_format($number, $precision);
    }


    /**
     * Returns an option array where every element has its value and label filled in
     * with elements from passed array
     *
     * @param array $data
     * @return array
     */
    public function getOptionArray(array $data)
    {
        $options = array();

        foreach ($data as $value) {
            $options[] = array('value' => $value, 'label' => $value);
        }

        return $options;
    }


    /**
     * Returns CSS class based on response status code
     *
     * @param int $statusCode
     * @return string
     */
    public function getStatusCodeClass($statusCode)
    {
        $cssClass = 'status-success';
        if ($statusCode > 399) {
            $cssClass = 'status-error';
        } else if ($statusCode > 299) {
            $cssClass = 'status-warning';
        }

        return $cssClass;
    }

}
