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
 * Class MP_Debug_Block_View_Sidebar
 */
class MP_Debug_Block_View_Sidebar extends MP_Debug_Block_View
{

    /**
     * Returns available http method filters
     *
     * @return array
     */
    public function getHttpMethodOptions()
    {
        return $this->getOptionArray(Mage::helper('mp_debug/filter')->getHttpMethodValues());
    }


    /**
     * Returns html for http methods select
     *
     * @return string
     * @throws Exception
     */
    public function getHttpMethodsSelect()
    {
        $options = $this->getHttpMethodOptions();
        array_unshift($options, array('value' => '', 'label' => 'Any'));

        /** @var Mage_Core_Block_Html_Select $select */
        $select = $this->getLayout()->createBlock('core/html_select');

        $select->setName('method')
            ->setId('method')
            ->setValue($this->getRequest()->getParam('method'))
            ->setOptions($options);

        return $select->getHtml();
    }


    /**
     * Returns html for limit selects
     *
     * @return string
     * @throws Exception
     */
    public function getLimitOptionsSelect()
    {
        /** @var MP_Debug_Helper_Filter $filterHelper */
        $filterHelper = Mage::helper('mp_debug/filter');

        /** @var Mage_Core_Block_Html_Select $select */
        $select = $this->getLayout()->createBlock('core/html_select');

        $select->setName('limit')
            ->setId('limit')
            ->setValue($this->getRequest()->getParam('limit', $filterHelper->getLimitDefaultValue()))
            ->setOptions($this->getOptionArray($filterHelper->getLimitValues()));

        return $select->getHtml();
    }

}
