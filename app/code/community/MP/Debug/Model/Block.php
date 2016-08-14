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
 * Class MP_Debug_Model_Block
 */
class MP_Debug_Model_Block
{
    public static $startRenderingTime;
    public static $endRenderingTime;

    /** @var string */
    protected $parentName;
    /** @var string */
    protected $name;
    /** @var string */
    protected $class;
    /** @var string */
    protected $templateFile;
    /** @var bool */
    protected $isRendering = false;
    /** @var int */
    protected $renderedAt = 0;
    /** @var int */
    protected $renderedCount;
    protected $renderedCompletedAt;
    protected $renderedDuration = 0;
    /** @var array */
    protected $data = array();


    public function init(Mage_Core_Block_Abstract $block)
    {
        $this->parentName = $block->getParentBlock() ? $block->getParentBlock()->getNameInLayout() : '';
        $this->name = $block->getNameInLayout();
        $this->class = get_class($block);
        $this->templateFile = $block instanceof Mage_Core_Block_Template ? $block->getTemplateFile() : '';
        // TODO: make sure we copy only serializable data
//        $this->data = $block->getData();
    }


    /**
     * Captures rendering start for specified block
     *
     * @param Mage_Core_Block_Abstract $block
     * @throws Exception
     */
    public function startRendering(Mage_Core_Block_Abstract $block)
    {
        if ($this->isRendering) {
            // Recursive block instances with same name is used - we don't update render start time
            $this->renderedCount++;

            Mage::log("Recursive block rendering {$this->getName()}", Zend_Log::DEBUG);
            return;
        }

        // Re-init data from block (some extension might update dynamically block's template)
        $this->init($block);

        $this->isRendering = true;
        $this->renderedCount++;
        $this->renderedAt = microtime(true);

        if (self::$startRenderingTime===null) {
            self::$startRenderingTime = $this->renderedAt;
        }
    }


    /**
     * Captures rendering completion for specified block
     *
     * @param Mage_Core_Block_Abstract $block
     */
    public function completeRendering(Mage_Core_Block_Abstract $block)
    {
        $this->isRendering = false;
        $this->renderedCompletedAt = microtime(true);
        $this->renderedDuration += ($this->renderedCompletedAt * 1000 - $this->renderedAt * 1000);

        self::$endRenderingTime = $this->renderedCompletedAt;
    }


    /**
     * Returns block's name in layout
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Returns block's class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Returns block's template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->templateFile;
    }


    /**
     * Checks if current block is rendering
     *
     * @return boolean
     */
    public function isRendering()
    {
        return $this->isRendering;
    }


    /**
     * Return timestamp when block rendering started
     *
     * @return int
     */
    public function getRenderedAt()
    {
        return $this->renderedAt;
    }


    /**
     * Returns timestamp when block rendering completed
     *
     * @return int
     */
    public function getRenderedCompletedAt()
    {
        return $this->renderedCompletedAt;
    }


    /**
     * Returns rendering duration in microseconds
     *
     * @return mixed
     */
    public function getRenderedDuration()
    {
        return $this->renderedDuration;
    }


    /**
     * Returns how many time a block with same name was rendered
     *
     * @return int
     */
    public function getRenderedCount()
    {
        return $this->renderedCount;
    }


    /**
     * Returns blocks associated data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Returns rendering time for all blocks in microseconds
     *
     * @return mixed
     */
    static public function getTotalRenderingTime()
    {
        return self::$endRenderingTime * 1000 - self::$startRenderingTime * 1000;
    }

    /**
     * @return string
     */
    public function getParentName()
    {
        return $this->parentName;
    }

}
