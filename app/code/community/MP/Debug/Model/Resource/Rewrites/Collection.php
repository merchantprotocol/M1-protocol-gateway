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
 * Class MP_Debug_Model_Resource_Collection
 */
class MP_Debug_Model_Resource_Rewrites_Collection extends MP_Debug_Model_Resource_NonDbCollection
{
	/**
	 * (non-PHPdoc)
	 * @see MP_Debug_Model_Resource_NonDbCollection::_getColumnsValue()
	 */
    protected function _getColumnsValue($item, $column)
    {
        if ($column == 'rewrites')
        {
            $data = $item->getData($column);
            $result = false;
            
            if (!isset($data['classes'])) {
                return $result;
            }
            
            $classes = $data['classes'];
            
            foreach($classes as $class) {
                if (!$result || $class['conflict'] == MP_Debug_Model_Rewrites::NO_CONFLICT_TYPE) {
                    $result = $class['class'];
                }
            }

            return $result;
        } else {
            return parent::_getColumnsValue($item, $column);
        }
    }
}
