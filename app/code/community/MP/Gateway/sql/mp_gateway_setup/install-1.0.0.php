<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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