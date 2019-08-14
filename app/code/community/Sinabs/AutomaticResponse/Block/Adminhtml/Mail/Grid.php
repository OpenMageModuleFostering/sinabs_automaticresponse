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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Sinabs
 * @package     Sinabs_AutomaticResponse
 * @copyright   Copyright (c) 2012 Sinabs (http://www.sinabs.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Sinabs_AutomaticResponse_Block_Adminhtml_Mail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('mailGrid');
		$this->setDefaultSort('mail_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('automaticresponse/mail')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('mail_id', array(
			'header' => Mage::helper('automaticresponse')->__('ID'),
			'width' => '50px',
			'type' => 'number',
			'index' => 'mail_id'
		));
		
		$this->addColumn('created_at', array(
			'header' => Mage::helper('automaticresponse')->__('Date'),
			'type' => 'date',
			'align' => 'left',
			'index' => 'created_at'
		));
		
		$this->addColumn('name', array(
			'header' => Mage::helper('automaticresponse')->__('Name'),
			'align' => 'left',
			'index' => 'name'
		));
		
		$this->addColumn('email', array(
			'header' => Mage::helper('automaticresponse')->__('Email'),
			'align' => 'left',
			'index' => 'email'
		));
		
		$this->addColumn('phone', array(
			'header' => Mage::helper('automaticresponse')->__('Phone'),
			'align' => 'left',
			'index' => 'phone'
		));
		
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('mail_id');
		$this->getMassactionBlock()->setFormFieldName('mail');
		
		$this->getMassactionBlock()->addItem('delete', array(
			'label' => $this->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
			'confirm' => $this->__('Are you sure?')
		));
	}
	
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/view', array('mail' => $row->getId()));
	}
}