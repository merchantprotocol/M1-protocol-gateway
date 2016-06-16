<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

 class MP_Gateway_Block_Adminhtml_Customer_Edit_Tab_Card 
 	extends Mage_Adminhtml_Block_Widget_Form
	implements Mage_Adminhtml_Block_Widget_Tab_Interface
 {
 	public function __construct()
 	{
 		parent::__construct();
 		$this->setTemplate('mp_gateway/customer/tab/cards.phtml');
 	}
 
 	public function getRegionsUrl()
 	{
 		return $this->getUrl('*/json/countryRegion');
 	}
 
 	protected function _prepareLayout()
 	{
 		$this->setChild('delete_button',
 				$this->getLayout()->createBlock('adminhtml/widget_button')
 				->setData(array(
 						'label'  => Mage::helper('customer')->__('Delete Card'),
 						'name'   => 'delete_address',
 						'element_name' => 'delete_address',
 						'disabled' => $this->isReadonly(),
 						'class'  => 'delete' . ($this->isReadonly() ? ' disabled' : '')
 				))
 				);
 		$this->setChild('add_card_button',
 				$this->getLayout()->createBlock('adminhtml/widget_button')
 				->setData(array(
 						'label'  => Mage::helper('customer')->__('Add New Card'),
 						'id'     => 'add_card_button',
 						'name'   => 'add_card_button',
 						'element_name' => 'add_card_button',
 						'disabled' => $this->isReadonly(),
 						'class'  => 'add'  . ($this->isReadonly() ? ' disabled' : ''),
 						'onclick'=> 'customerCards.addNewCard()'
 				))
 				);
 		$this->setChild('cancel_button',
 				$this->getLayout()->createBlock('adminhtml/widget_button')
 				->setData(array(
 						'label'  => Mage::helper('customer')->__('Cancel'),
 						'id'     => 'cancel_add_card'.$this->getTemplatePrefix(),
 						'name'   => 'cancel_card',
 						'element_name' => 'cancel_card',
 						'class'  => 'cancel delete-address'  . ($this->isReadonly() ? ' disabled' : ''),
 						'disabled' => $this->isReadonly(),
 						'onclick'=> 'customerCards.cancelAdd(this)',
 				))
 				);
 		return parent::_prepareLayout();
 	}
 
 	/**
 	 * Check block is readonly.
 	 *
 	 * @return boolean
 	 */
 	public function isReadonly()
 	{
 		$customer = Mage::registry('current_customer');
 		return $customer->isReadonly();
 	}
 
 	public function getDeleteButtonHtml()
 	{
 		return $this->getChildHtml('delete_button');
 	}
 	
 	protected function _beforeToHtml(){
 		$this->initForm();
 		
 		return parent::_beforeToHtml();
 	}
 
 	/**
 	 * Initialize form object
 	 *
 	 * @return MP_Gateway_Block_Adminhtml_Customer_Edit_Tab_Card
 	 */
 	public function initForm()
 	{
 		/* @var $customer Mage_Customer_Model_Customer */
 		$customer = Mage::registry('current_customer');
 
 		$form = new Varien_Data_Form();
 		$fieldset = $form->addFieldset('card_fieldset', array(
 				'legend'    => Mage::helper('customer')->__("Edit Customer's Card"))
 				);
 		
 	    $ccTypes = Mage::getStoreConfig('payment/mp_gateway/cctypes');
 	    $allowed = explode(',',$ccTypes);
        $values = array();

        foreach (Mage::getSingleton('payment/config')->getCcTypes() as $code => $name) {
            if (in_array($code, $allowed) || !count($allowed)) {
                $values[] = array(
                   'value' => $code,
                   'label' => $name
                );
            }
        }

 		$fieldset->addField ( 'card_type', 'select', array (
 				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Card Type' ),
 				'name' => 'card_type',
 				'required' => true,
 				'values' => $values,
 		) );
 		
		$fieldset->addField ( 'card_number', 'text', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Card Number' ),
				'name' => 'card_number',
				'required' => true 
		) );

		$fieldset->addField ( 'expmon', 'text', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Expiration Month' ),
				'name' => 'expmon',
				'required' => true
		) );
		
		$fieldset->addField ( 'expyear', 'text', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Expiration Year' ),
				'name' => 'expyear',
				'required' => true
		) );
		
 		$cardModel = Mage::getModel('mp_gateway/card');
 		$cardCollection = $cardModel->getCollection();
 		$cardCollection->addFieldToFilter('customer_id',$customer->getId());
 		$data = array();
 		if ($cardCollection->getSize()>0){
 			foreach ($cardCollection as $card){
 				$cardDataEnc = $card->getCardData();
 				$cardDataSer = Mage::getModel('core/encryption')->decrypt($cardDataEnc);
 				$cardDataArray = unserialize($cardDataSer);
 				$cardType = $cardDataArray['card_type'];
 				$card->setCardType($cardType);
 				$cardNumber = 'XXXX-XXXX-XXXX-'.$cardDataArray['last4'];
 				$card->setCardNumber($cardNumber);
 				$expmon = $cardDataArray['expmon'];
 				$card->setExpmon($expmon);
 				$expyear = $cardDataArray['expyear'];
 				$card->setExpyear($expyear);
 			}
 		}
 			
 		$this->assign('customer', $customer);
 		$this->assign('cardCollection', $cardCollection);
 		$this->setForm($form);
 
 		return $this;
 	}
 
 	public function getCancelButtonHtml()
 	{
 		return $this->getChildHtml('cancel_button');
 	}
 
 	public function getAddNewButtonHtml()
 	{
 		return $this->getChildHtml('add_card_button');
 	}
 
 	public function getTemplatePrefix()
 	{
 		return '_template_';
 	}
 
 	/**
 	 * Return predefined additional element types
 	 *
 	 * @return array
 	 */
 	protected function _getAdditionalElementTypes()
 	{
 		return array(
 				'file'      => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_file'),
 				'image'     => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_image'),
 				'boolean'   => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_boolean'),
 		);
 	}
  
 	/**
 	 * Add specified values to name prefix element values
 	 *
 	 * @param string|int|array $values
 	 * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
 	 */
 	public function addValuesToNamePrefixElement($values)
 	{
 		if ($this->getForm() && $this->getForm()->getElement('prefix')) {
 			$this->getForm()->getElement('prefix')->addElementValues($values);
 		}
 		return $this;
 	}
 
 	/**
 	 * Add specified values to name suffix element values
 	 *
 	 * @param string|int|array $values
 	 * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
 	 */
 	public function addValuesToNameSuffixElement($values)
 	{
 		if ($this->getForm() && $this->getForm()->getElement('suffix')) {
 			$this->getForm()->getElement('suffix')->addElementValues($values);
 		}
 		return $this;
 	}
 	
 	public function getTabLabel()
 	{
 		return Mage::helper('customer')->__('Saved Cards');
 	}
 	
 	public function getTabTitle()
 	{
 		return Mage::helper('customer')->__('Saved Cards');
 	}
 	
 	public function canShowTab()
 	{
 		if (Mage::registry('current_customer')->getId()) {
 			return true;
 		}
 		return false;
 	}
 	
 	public function isHidden()
 	{
 		if (Mage::registry('current_customer')->getId()) {
 			return false;
 		}
 		return true;
 	} 	
 }
 