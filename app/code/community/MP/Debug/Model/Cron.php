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
 * Class MP_Debug_Model_Cron
 */
class MP_Debug_Model_Cron
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Cron jobs that deletes expired request info
     */
    public function deleteExpiredRequests()
    {
        $helper = Mage::helper('mp_debug');
        if (!$helper->isEnabled()) {
            return 'skipped: module is disabled.';
        }

        if ($helper->getPersistLifetime() == 0) {
            return 'skipped: lifetime is set to 0';
        }

        $expirationDate = $this->getExpirationDate(date(self::DATE_FORMAT));
        $table = $this->getRequestsTable();
        $deleteSql = "DELETE FROM {$table} WHERE date <= '{$expirationDate}'";

        /** @var Magento_Db_Adapter_Pdo_Mysql $connection */
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        /** @var Varien_Db_Statement_Pdo_Mysql $result */
        $result = $connection->query($deleteSql);
        return "{$result->rowCount()} requests deleted";
    }


    /**
     * Returns request info table
     *
     * @return string
     */
    public function getRequestsTable()
    {
        return Mage::getResourceModel('mp_debug/requestInfo')->getMainTable();
    }


    /**
     * Removes configured number of days from specified date
     *
     * @param string $currentDate
     * @return bool|string
     */
    public function getExpirationDate($currentDate)
    {
        $numberOfDays = Mage::helper('mp_debug')->getPersistLifetime();

        return date(self::DATE_FORMAT, strtotime("-{$numberOfDays} days {$currentDate}"));
    }

}
