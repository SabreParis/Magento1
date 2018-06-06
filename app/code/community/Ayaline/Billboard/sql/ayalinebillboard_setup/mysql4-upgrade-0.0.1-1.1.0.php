<?php
/**
 * created : 7 mai 2012
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/* @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard_customer_group')}` (
	`billboard_id`		INT(10) 		UNSIGNED NOT NULL ,
	`customer_group_id`	SMALLINT(5)		UNSIGNED NOT NULL ,
	PRIMARY KEY (`billboard_id`, `customer_group_id`) ,
	INDEX `FK_AYALINE_BILLBOARD_CUSTOMER_GROUP_BILLBOARD_ID` (`billboard_id` ASC) ,
	INDEX `FK_AYALINE_BILLBOARD_CUSTOMER_GROUP_CUSTOMER_GROUP_ID` (`customer_group_id` ASC) ,
	CONSTRAINT `FK_AYALINE_BILLBOARD_CUSTOMER_GROUP_BILLBOARD_ID`
		FOREIGN KEY (`billboard_id`)
		REFERENCES `{$this->getTable('ayalinebillboard/billboard')}` (`billboard_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `FK_AYALINE_BILLBOARD_CUSTOMER_GROUP_CUSTOMER_GROUP_ID`
		FOREIGN KEY (`customer_group_id`)
		REFERENCES `{$this->getTable('customer/customer_group')}` (`customer_group_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

");

$this->endSetup();