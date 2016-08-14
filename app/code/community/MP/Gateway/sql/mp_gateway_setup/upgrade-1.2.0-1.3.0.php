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

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$attributeKey = 'enable_savedcards';

$setup->addAttribute('customer', $attributeKey, array(
    'type'      => 'int',
    'label'     => 'Enable Saved Cards',
    'input'     => 'boolean',
    'backend'   => 'customer/attribute_backend_data_boolean',
    'visible'   => 1,
    'required'  => 0,
    'position'  => 30,
    'note'      => 'Payment method must also allow saved cards'
));

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeKey);
$attribute->setData('used_in_forms', array('adminhtml_customer', 'customer_account_edit'));
$attribute->setData('sort_order', 30);
$attribute->save();

$installer->endSetup();
