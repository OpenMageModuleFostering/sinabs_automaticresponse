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
class Sinabs_AutomaticResponse_Model_Observer
{
	/**
	 * Send automatic response
	 *
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function sendAutomaticResponse(Varien_Event_Observer $observer)
	{
		if (!Mage::getStoreConfig(Sinabs_AutomaticResponse_Helper_Data::XML_PATH_AUTOMATICRESPONSE_GENERAL_ENABLED)) {
			return;
		}
		
		$controllerAction = $observer->getControllerAction();
		
		$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(false);
		
		$storeId = Mage::app()->getStore()->getId();
		
		$sender = Mage::getStoreConfig(Sinabs_AutomaticResponse_Helper_Data::XML_PATH_AUTOMATICRESPONSE_GENERAL_SENDER, $storeId);
		$replyTo = Mage::getStoreConfig('trans_email/ident_' . $sender . '/email', $storeId);
		
		try {
			$mailTemplate = Mage::getModel('core/email_template');
			$mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId));
			$mailTemplate->setReplyTo($replyTo);
			$mailTemplate->sendTransactional(
	            Mage::getStoreConfig(Sinabs_AutomaticResponse_Helper_Data::XML_PATH_AUTOMATICRESPONSE_GENERAL_TEMPLATE, $storeId),
	            $sender,
	            $controllerAction->getRequest()->getParam('email'),
	            null,
	            array(
	            	'email' => $email,
	            	'name' => $controllerAction->getRequest()->getParam('name'),
	            	'comment' => $controllerAction->getRequest()->getParam('comment')
	            ),
	            $storeId
	     	);	
		} catch (Exception $e) {
            Mage::logException($e);
            return false;
		}
	     
	    $translate->setTranslateInline(true);
	}
	
	/**
	 * Save Contacts email in DB
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function saveEmail(Varien_Event_Observer $observer)
	{
		if (!Mage::getStoreConfig(Sinabs_AutomaticResponse_Helper_Data::XML_PATH_AUTOMATICRESPONSE_BACKUP_ACTIVE)) {
			return;
		}
		
		$request = $observer->getControllerAction()->getRequest();
		$model = Mage::getModel('automaticresponse/mail');
		
		$model->setCreatedAt(now());
		$model->setName($request->getParam('name'));
		$model->setEmail($request->getParam('email'));
		$model->setPhone($request->getParam('telephone'));
		$model->setComment($request->getParam('comment'));
		
		try {
			$model->save();
		} catch (Exception $e) {
			Mage::logException($e->getMessage());
		}
	}
}