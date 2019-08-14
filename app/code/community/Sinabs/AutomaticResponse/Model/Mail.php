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
class Sinabs_AutomaticResponse_Model_Mail extends Mage_Core_Model_Abstract
{	
	/**
	 * Initialize Sinabs_AutomaticResponse_Model_Mail
	 *
	 */
	public function _construct()
	{
		parent::_construct();
		$this->_init('automaticresponse/mail');
	}
	
	/**
	 * Send comment and notify customer
	 *
	 * @param string $email
	 * @param string $name
	 * @param string $comment
	 * @return Sinabs_AutomaticResponse_Model_Mail
	 */
	public function sendComment($email, $name, $prevComment, $comment)
	{
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
	            Mage::getStoreConfig(Sinabs_AutomaticResponse_Helper_Data::XML_PATH_AUTOMATICRESPONSE_GENERAL_TEMPLATE_COMMENT, $storeId),
	            $sender,
	            $email,
	            null,
	            array(
	            	'comment' => $comment,
	            	'email' => $email,
	            	'name' => $name,
	            	'comment_client' => $prevComment
	            ),
	            $storeId
	     	);	
		} catch (Exception $e) {
            Mage::logException($e);
            return false;
		}
	     
	    $translate->setTranslateInline(true);
	    return $this;
	}
}