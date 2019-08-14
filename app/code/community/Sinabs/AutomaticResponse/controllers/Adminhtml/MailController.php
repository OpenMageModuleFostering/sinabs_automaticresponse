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
class Sinabs_AutomaticResponse_Adminhtml_MailController extends Mage_Adminhtml_Controller_Action
{
	public function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('system');
		return $this;
	}
	
	public function indexAction()
	{
		$this->_forward('list');
	}
	
	public function listAction()
	{
		$this->_initAction()->_addContent($this->getLayout()->createBlock('automaticresponse/adminhtml_mail'));
		$this->renderLayout();
	}
	
	public function viewAction()
	{
		if (!$mail = $this->_initMail()) {
			return;
		}
		
		$this->_initAction()->renderLayout();
	}
	
	public function deleteAction()
	{
		if($id = $this->getRequest()->getParam('mail')) {
			try {
				Mage::getModel('automaticresponse/mail')->load($id)->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess('The mail has been deleted');
				$this->_Redirect('*/*/list');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/list');
				return;
			}
		}
	}
	
	public function massDeleteAction()
	{
		$ids = $this->getRequest()->getParam('mail');
		if (!is_array($ids)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select mail(s)'));
		} else {
			try {
				foreach ($ids as $id) {
					Mage::getModel('automaticresponse/mail')->load($id)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($ids)));
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	public function sendCommentAction() 
	{
		$comment = $this->getRequest()->getParam('comment', false);
		$id = $this->getRequest()->getParam('id');
		$isCustomerNotified = $this->getRequest()->getParam('is_customer_notified');
		
		$mail = Mage::getModel('automaticresponse/mail')->load($id);
		
		if ($mail->getReply()) {
			$reply = unserialize($mail->getReply());
		} else {
			$reply = array();
		}
		
		$reply[] = array(
			'comment' => $comment,
			'created_at' => now(),
			'is_customer_notified' => $isCustomerNotified
		);
		
		try {
			$mail->setReply(serialize($reply));
			$mail->save();
			
			if ($isCustomerNotified) {
				Mage::getModel('automaticresponse/mail')->sendComment($mail->getEmail(), $mail->getName(), $mail->getComment(), $comment);
			}
			
			Mage::register('current_mail', $mail);
			
			$this->loadLayout('empty');
			$this->renderLayout();
		} catch (Exception $e) {
			$this->getResponse()->setBody(Zend_Json::encode(array(
				'error' => true,
				'message' => $e->getMessage()
			)));
		}
	}
	
	protected function _initMail()
	{
		$mailId = $this->getRequest()->getParam('mail');
		if ($mailId) {
			$mail = Mage::getModel('automaticresponse/mail')->load($mailId);
			if (!$mail->getId()) {
				$this->_getSession()->addError($this->__('Wrong mail ID specified.'));
				$this->_redirect('*/*/');
				return false;
			}
		}
		Mage::register('current_mail', $mail);
		return $mail;
	}
}