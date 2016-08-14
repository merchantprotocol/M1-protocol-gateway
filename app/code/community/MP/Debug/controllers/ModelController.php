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
 * Class MP_Debug_ModelController
 */
class MP_Debug_ModelController extends MP_Debug_Controller_Front_Action
{

    /**
     * Enable SQL profiler
     */
    public function enableSqlProfilerAction()
    {
        try {
            $this->getService()->setSqlProfilerStatus(true);
            $this->getService()->flushCache();

            Mage::getSingleton('core/session')->addSuccess('SQL profiler was enabled.');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to enable SQL profiler: ' . $e->getMessage());
        }

        $this->_redirectReferer();
    }


    /**
     * Disable SQL profiler
     */
    public function disableSqlProfilerAction()
    {
        try {
            $this->getService()->setSqlProfilerStatus(false);
            $this->getService()->flushCache();

            Mage::getSingleton('core/session')->addSuccess('SQL profiler was disabled.');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to disable SQL profiler: ' . $e->getMessage());
        }

        $this->_redirectReferer();
    }


    /**
     * Runs specified SQL
     */
    public function selectSqlAction()
    {
        if ($query = $this->_initQuery()) {
            $helper = Mage::helper('mp_debug');
            $results = $helper->runSql($query->getQuery(), $query->getQueryParams());
            $this->renderTable($results);
        }
    }


    /**
     * Runs DESCRIBE for specified SQL
     */
    public function describeSqlAction()
    {
        if ($query = $this->_initQuery()) {
            $helper = Mage::helper('mp_debug');
            $results = $helper->runSql('EXPLAIN EXTENDED ' . $query->getQuery(), $query->getQueryParams());
            $this->renderTable($results);
        }
    }


    /**
     * Returns stack trace for specified query
     */
    public function stacktraceSqlAction()
    {
        if ($query = $this->_initQuery()) {
            $helper = Mage::helper('mp_debug');
            $stripZendPath = $helper->canStripZendDbTrace() ? 'lib/Zend/Db/Adapter' : '';
            $trimPath = $helper->canTrimMagentoBaseDir() ? Mage::getBaseDir() . DS : '';
            $html = '<pre>' . Mage::helper('mp_debug')->formatStacktrace($query->getStackTrace(), $stripZendPath, $trimPath) . '</pre>';
            $this->getResponse()->setBody($html);
        }
    }

    /**
     * Returns query referenced in request parameters
     *
     * @return MP_Debug_Model_Query
     */
    protected function _initQuery()
    {
        $token = $this->getRequest()->getParam('token');
        $index = $this->getRequest()->getParam('index');

        if ($token === null || $index === null) {
            $this->getResponse()->setHttpResponseCode(400)->setBody('Invalid parameters');
            return null;
        }

        /** @var MP_Debug_Model_RequestInfo $requestProfile */
        $requestProfile = Mage::getModel('mp_debug/requestInfo')->load($token, 'token');
        if (!$requestProfile->getId()) {
            $this->getResponse()->setHttpResponseCode(404)->setBody('Request profile not found');
            return null;
        }

        $queries = $requestProfile->getQueries();
        if (!$queries || !($index < count($queries))) {
            $this->getResponse()->setHttpResponseCode(404)->setBody('Query not found');
            return null;
        }

        /** @var Zend_Db_Profiler_Query $query */
        return $queries[(int)$index];
    }

}
