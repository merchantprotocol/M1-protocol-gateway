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
 * @package    MP_Gateway
 * @copyright  Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
 * @license    https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
 */

/**
 * @author Fran Mayers (https://merchantprotocol.com)
 */

$installer = $this;
$installer->startSetup();

$customerTable = $installer->getTable('customer/entity');

$installer->run("

    CREATE TABLE IF NOT EXISTS `{$this->getTable('mp_gateway/card')}` (
        `entity_id` int(11) unsigned NOT NULL auto_increment,
        `customer_id` int(11) unsigned NOT NULL,
        `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
        `card_data` text,
        `is_default` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
      PRIMARY KEY (`entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ALTER TABLE `{$this->getTable('mp_gateway/card')}` ADD FOREIGN KEY (`customer_id`) REFERENCES `{$customerTable}`(`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE;

");
$installer->endSetup();
