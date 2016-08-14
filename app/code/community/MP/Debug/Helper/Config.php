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
 * Class MP_Debug_Helper_Config
 */
class MP_Debug_Helper_Config extends Mage_Core_Helper_Abstract
{

    /**
     * Returns Magento version
     *
     * @return string
     */
    public function getMagentoVersion()
    {
        return Mage::getVersion();
    }


    /**
     * Returns PHP version
     *
     * @return string
     */
    public function getPhpVersion()
    {
        return phpversion();
    }


    /**
     * Returns a list of php extensions required by current Magento version
     *
     * @return array
     */
    public function getExtensionRequirements()
    {
        return array('spl', 'dom', 'simplexml', 'mcrypt', 'hash', 'curl', 'iconv', 'ctype', 'gd', 'soap', 'mbstring');
    }


    /**
     * Returns enable state for required PHP extensions
     *
     * @return array
     */
    public function getExtensionStatus()
    {
        $status = array();

        $extensions = $this->getExtensionRequirements();
        foreach ($extensions as $extension) {
            $status [$extension] = extension_loaded($extension);
        }

        return $status;
    }


    /**
     * Returns description for installed Magento modules
     *
     * @return array
     */
    public function getModules()
    {
        $items = array();
        $items[] = array(
            'module'   => 'Magento',
            'codePool' => 'core',
            'active'   => true,
            'version'  => $this->getMagentoVersion()
        );

        $modulesConfig = Mage::getConfig()->getModuleConfig();
        foreach ($modulesConfig as $node) {
            foreach ($node as $module => $data) {
                $items[] = array(
                    'module'   => $module,
                    'codePool' => (string)$data->codePool,
                    'active'   => $data->active == 'true',
                    'version'  => (string)$data->version
                );
            }
        }

        return $items;
    }

}
