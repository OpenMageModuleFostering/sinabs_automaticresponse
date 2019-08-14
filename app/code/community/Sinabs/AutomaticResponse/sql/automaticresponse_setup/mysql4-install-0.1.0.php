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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("
	CREATE TABLE  {$this->getTable('sinabs_automaticresponse_mail')} (
	`mail_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR(255) NOT NULL ,
	`email` VARCHAR(255) NOT NULL ,
	`phone` VARCHAR(255) NOT NULL ,
	`comment` TEXT NOT NULL ,
	PRIMARY KEY (`mail_id`)
	) ENGINE = INNODB;
");

$installer->endSetup();