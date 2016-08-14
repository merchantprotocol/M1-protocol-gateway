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
 * Class MP_Debug_Model_Model
 */
class MP_Debug_Model_Model
{
    protected $class;
    protected $resource;
    protected $count;


    /**
     * Captures information about specified model
     *
     * @param Mage_Core_Model_Abstract $model
     */
    public function init(Mage_Core_Model_Abstract $model)
    {
        $this->class = get_class($model);
        $this->resource = $model->getResourceName();
        $this->count = 0;
    }


    /**
     * Returns model's class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Increments number of times this model was loaded
     */
    public function incrementCount()
    {
        $this->count++;
    }


    /**
     * Returns model's resource name
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }


    /**
     * Returns model's number of loads
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

}
