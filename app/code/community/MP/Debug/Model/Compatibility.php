<?php

/**
 * Class MP_Debug_Model_Compatibility
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Model_Compatibility extends Mage_Core_Model_Abstract
{
    const NO_CONFLICT_TYPE       = 0;
    const RESOLVED_CONFLICT_TYPE = 1;
    const CONFLICT_TYPE          = 2;
    
    const NO_CONFLICT_COLOR       = 'green';
    const RESOLVED_CONFLICT_COLOR = 'grey';
    const CONFLICT_COLOR          = 'red';
    
    const USE_CACHE 	= false;
    const CACHE_TYPE 	= "config";
    const CACHE_KEY 	= "MP_DEBUG_MODULE_COMPATIBILITY";
    
    protected $_compatibilityList = array(
    		'Innoexts_AdvancedPricing' => array(
    				'1.3.1.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    						'Innoexts_ZonePricing' 			=> 2,
	    				)
    				)
    		),
    		'Innoexts_AdvancedDataflow' => array(
    				'1.0.3.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_CustomerGroup' => array(
    				'1.0.0.4' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_AdvancedDataflow' 	=> 2,
	    						'Innoexts_CurrencyGeoFilter' 	=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_ShippingTablerate' 	=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    						'Innoexts_ZonePricing' 			=> 2,
	    				)
    				)
    		),
    		'Innoexts_CurrencyGeoFilter' => array(
    				'1.3.0.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_CurrencyPricing' => array(
    				'1.3.1.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_ProductBaseCurrency' => array(
    				'1.0.1.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_AdvancedDataflow' 	=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_ShippingTablerate' 	=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_ShippingTablerate' => array(
    				'1.0.2' => array(
	    				'compatibility' => array(
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_StorePricing' => array(
    				'1.2.0.2' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_Warehouse' => array(
    				'1.2.5.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    		'Innoexts_WarehousePlus' => array(
    				'1.2.5.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    						'Innoexts_ZonePricing' 			=> 2,
	    				)
    				)
    		),
    		'Innoexts_Zone' => array(
    				'1.0.1.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_AdvancedDataflow' 	=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_CurrencyGeoFilter' 	=> 2,
	    						'Innoexts_CurrencyPricing' 		=> 2,
	    						'Innoexts_ShippingTablerate' 	=> 2,
	    						'Innoexts_StorePricing' 		=> 2,
	    						'Innoexts_Warehouse' 			=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    						'Innoexts_ZonePricing' 			=> 2,
	    				)
    				)
    		),
    		'Innoexts_ZonePricing' => array(
    				'1.3.0.0' => array(
	    				'compatibility' => array(
	    						'Innoexts_AdvancedPricing' 		=> 2,
	    						'Innoexts_CustomerGroup' 		=> 2,
	    						'Innoexts_WarehousePlus' 		=> 2,
	    						'Innoexts_Zone' 				=> 2,
	    				)
    				)
    		),
    );
    
    public function useCache()
    {
        return Mage::app()->useCache(self::CACHE_TYPE) && self::USE_CACHE;
    }

    public function getConflictTypes()
    {
        $types = array(
                    self::NO_CONFLICT_TYPE       => Mage::helper('mp_debug/conflicts')->__('No'),
                    self::CONFLICT_TYPE          => Mage::helper('mp_debug/conflicts')->__('Yes'),
                    self::RESOLVED_CONFLICT_TYPE => Mage::helper('mp_debug/conflicts')->__('Resolved'),
                );

        return $types;
    }    

    /**
     * 
     * @return Ambigous <mixed, Mage_Core_Model_Abstract, false>
     */
    public function getCollection()
    {
        if ($this->useCache() && $cache = Mage::app()->loadCache(self::CACHE_KEY)) {
            $collection = unserialize($cache);
        } else {
            $collection = Mage::getModel('mp_debug/resource_rewrites_collection');
            
            foreach($this->_getCompatArray() as $rewrite) {
                $collection->addItem($rewrite);
            }
            
            if ($this->useCache()) {
                Mage::app()->saveCache(serialize($collection), self::CACHE_KEY, array(self::CACHE_TYPE));
            }
        }        

        return $collection;
    }

    /**
     * 
     * @return multitype:Varien_Object
     */
    protected function _getCompatArray()
    {
        $rewritesArray = array();

        $rewrites = $this->_collectModules();
        foreach($rewrites as $initialClass => $compatData) {
           $rewriteItem = new Varien_Object();
           $rewriteItem->setClass($initialClass);
           $rewriteItem->setActive($compatData['active']);
           $rewriteItem->setCodePool($compatData['codePool']);
           $rewriteItem->setVersion($compatData['version']);
           $rewriteItem->setCompatibility($compatData['compatibility']);
           $rewriteItem->setConflict($this->_getConflict($compatData['compatibility']));

           $rewritesArray[] = $rewriteItem;
        }

        return $rewritesArray;
    }

    /**
     * 
     * @param unknown $type
     * @return Ambigous <multitype:, string, multitype:string Ambigous <boolean, string> >
     */
    protected function _collectModules($type)
    {
        $modelsRewrites = array();
        
        $modules = Mage::getConfig()->getNode('modules')->children();
        
        foreach ($modules as $modName => $module)
        {
        	if (!isset($this->_compatibilityList[$modName])) continue;
        	
        	$modelsRewrites[$modName] = array();
        	$modelsRewrites[$modName]['active'] 	= (boolean)$module->active;
        	$modelsRewrites[$modName]['codePool'] 	= (string) $module->codePool;
        	$modelsRewrites[$modName]['version'] 	= (string) $module->version;
        	
            foreach($this->_compatibilityList[$modName] as $version => $versionData)
            {
                foreach($versionData['compatibility'] as $_mod => $_comp)
                {
                	if (!version_compare($modelsRewrites[$modName]['version'], $version, '>=')) continue;
                	
                	if ($_module = Mage::getConfig()->getNode('modules')->$_mod)
                	{
	                	if ($_comp === self::NO_CONFLICT_TYPE) {
	                		$color = self::NO_CONFLICT_COLOR;
	                	} elseif ($_comp === self::RESOLVED_CONFLICT_TYPE) {
	                		$color = self::RESOLVED_CONFLICT_COLOR;
	                	} elseif ($_comp === self::CONFLICT_TYPE) {
	                		$color = self::CONFLICT_COLOR;
	                	}
	                	$modelsRewrites[$modName]['compatibility'][$_mod] = array(
	                			'class'		=> $_mod,
	                			'color'    	=> $color,
	                			'conflict' 	=> $_comp,
	                	);
                	}
                }
            }
        }

        return $modelsRewrites;
    }
    
    /**
     * 
     * @param unknown $compatData
     * @return string
     */
    protected function _getConflict($compatData)
    {
        $conflict = self::NO_CONFLICT_TYPE;
        foreach($compatData as $compat) {
            if ($compat['conflict'] == self::CONFLICT_TYPE) {
                return self::CONFLICT_TYPE;
            } elseif ($compat['conflict'] == self::RESOLVED_CONFLICT_TYPE) {
                $conflict = self::RESOLVED_CONFLICT_TYPE;
            }
        }
        return $conflict;
    }
}