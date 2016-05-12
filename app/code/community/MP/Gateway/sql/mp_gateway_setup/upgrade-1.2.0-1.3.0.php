<?php
/**
 * @author Fran Mayers (https://merchantprotocol.com)
 * @copyright  Copyright (c) 2016 Merchant Protocol
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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