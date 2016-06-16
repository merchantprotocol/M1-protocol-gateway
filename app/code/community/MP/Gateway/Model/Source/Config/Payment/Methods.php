<?php
/**
 * 
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
class MP_Gateway_Model_Source_Config_Payment_Methods
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
    	$options = array();
    	foreach($this->getMethods() as $method)
    	{
    		$_option = array(
    				'value' => $method->getId(),
    				'label' => Mage::getStoreConfig('payment/'.$method->getId().'/title') ?: $method->getId()
    		);
    		$options[] = $_option;
    	}
    	return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
    	$options = array();
    	foreach($this->getMethods() as $method)
    	{
    		$options[] = $method->getId();
    	}
    	return $options;
    }
	
    /**
     * 
     */
    protected function getMethods()
    {
    	return Mage::getModel('payment/config')->getAllMethods(Mage::app()->getStore()->getId());
    }
}
