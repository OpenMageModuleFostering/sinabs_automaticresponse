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
class Sinabs_AutomaticResponse_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_AUTOMATICRESPONSE_GENERAL_ENABLED = 'automaticresponse/general/active';
	const XML_PATH_AUTOMATICRESPONSE_GENERAL_TEMPLATE = 'automaticresponse/general/template';
	const XML_PATH_AUTOMATICRESPONSE_GENERAL_SENDER = 'automaticresponse/general/sender_email_identity';
	const XML_PATH_AUTOMATICRESPONSE_BACKUP_ACTIVE = 'automaticresponse/backup/active';
	const XML_PATH_AUTOMATICRESPONSE_GENERAL_TEMPLATE_COMMENT = 'automaticresponse/general/template_comment';
	
	/**
	 * Sort Array By date DESC
	 *
	 * @param array $array
	 * @return array
	 */
	public function sortByDateDesc($array)
	{
		if (count($array) < 1) {
			return $array;
		}
		usort($array, array($this, '_callbackDateDesc'));
		return $array;
	}
	
	private function _callbackDateDesc($a, $b)
	{
		return strtotime($b['created_at']) - strtotime($a['created_at']);
	}
}