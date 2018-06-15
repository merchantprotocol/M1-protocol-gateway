<?php
/**
 * Mage Plugins
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mage Plugins Commercial License (MPCL 1.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://mageplugins.net/commercial-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mageplugins@gmail.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future. If you wish to customize the extension for your
 * needs please refer to https://www.mageplugins.net for more information.
 *
 * @category   MP
 * @package    MP_Gateway
 * @copyright  Copyright (c) 2006-2018 Mage Plugins Inc. and affiliates (https://mageplugins.net/)
 * @license    https://mageplugins.net/commercial-license/  Mage Plugins Commercial License (MPCL 1.0)
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
				'required' => true,
		) );

		$fieldset->addField ( 'expmon', 'select', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Expiration Month' ),
				'name' => 'expmon',
				'required' => true,
				'values' => array(
					array('value' => '01', 'label' => $this->__('January')),	
					array('value' => '02', 'label' => $this->__('February')),
					array('value' => '03', 'label' => $this->__('March')),
					array('value' => '04', 'label' => $this->__('April')),
					array('value' => '05', 'label' => $this->__('May')),
					array('value' => '06', 'label' => $this->__('June')),
					array('value' => '07', 'label' => $this->__('July')),
					array('value' => '08', 'label' => $this->__('August')),
					array('value' => '09', 'label' => $this->__('September')),
					array('value' => '10', 'label' => $this->__('October')),
					array('value' => '11', 'label' => $this->__('November')),
					array('value' => '12', 'label' => $this->__('December')),
				),
		) );
		
		$years = array();
		for ($year = date('Y'); $year < date('Y') + 10; $year++){
			$years[] = array('value' => $year, 'label' => $this->__($year));
		}
		
		$fieldset->addField ( 'expyear', 'select', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Expiration Year' ),
				'name' => 'expyear',
				'required' => true,
				'values' => $years,				
		) );

		$fieldset->addField ( 'cid', 'text', array (
				'label' => $this->helper ( 'mp_gateway' )->__ ( 'Card Security Code (CVV)' ),
				'name' => 'cid',
				'required' => false
		) );
		
 		$cardModel = Mage::getModel('mp_gateway/card');
 		$cardCollection = $cardModel->getCollection();
 		$cardCollection->addFieldToFilter('customer_id',$customer->getId());
 		$cardCollection->setOrder('is_default');
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
 
