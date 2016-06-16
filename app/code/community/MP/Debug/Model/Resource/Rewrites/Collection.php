<?php

/**
 * Class MP_Debug_Model_Resource_Collection
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
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