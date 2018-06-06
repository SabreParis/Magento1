<?php
/**
 * created : 10/08/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Billboard
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 *
 * @package Ayaline_Billboard
 */

/* @var $this Mage_Core_Model_Resource_Setup */

$this->startSetup();

$this->run("

CREATE  TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard')}` (
	`billboard_id`	INT(10)			UNSIGNED	NOT NULL AUTO_INCREMENT ,
	`identifier`	VARCHAR(255)				NOT NULL ,
	`title`			VARCHAR(255)				NOT NULL ,
	`content`		MEDIUMTEXT					NULL ,
	`created_at`	DATETIME					NULL ,
	`updated_at`	DATETIME					NULL ,
	`display_from`	DATETIME					NULL ,
	`display_to`	DATETIME					NULL ,
	`is_active`		TINYINT(1)					NOT NULL DEFAULT 1 ,
	PRIMARY KEY (`billboard_id`),
	INDEX `INDEX_AYALINE_BILLBOARD_IDENTIFIER` (`identifier` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


CREATE  TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard_store')}` (
	`billboard_id`	INT(10) 		UNSIGNED NOT NULL ,
	`store_id`		SMALLINT(5)		UNSIGNED NOT NULL ,
	PRIMARY KEY (`billboard_id`, `store_id`) ,
	INDEX `FK_AYALINE_BILLBOARD_STORE_BILLBOARD_ID` (`billboard_id` ASC) ,
	INDEX `FK_AYALINE_BILLBOARD_STORE_STORE_ID` (`store_id` ASC) ,
	CONSTRAINT `FK_AYALINE_BILLBOARD_STORE_BILLBOARD_ID`
		FOREIGN KEY (`billboard_id`)
		REFERENCES `{$this->getTable('ayalinebillboard/billboard')}` (`billboard_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `FK_AYALINE_BILLBOARD_STORE_STORE_ID`
		FOREIGN KEY (`store_id`)
		REFERENCES `{$this->getTable('core/store')}` (`store_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


CREATE  TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard_type')}` (
	`type_id`		INT(10)			UNSIGNED	NOT NULL AUTO_INCREMENT ,
	`identifier`	VARCHAR(255)				NOT NULL ,
	`title`			VARCHAR(255)				NOT NULL ,
	PRIMARY KEY (`type_id`),
	INDEX `INDEX_AYALINE_BILLBOARD_TYPE_IDENTIFIER` (`identifier` ASC) ,
	UNIQUE INDEX `UK_AYALINE_BILLBOARD_TYPE_IDENTIFIER` (`identifier` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


CREATE  TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard_type_index')}` (
	`billboard_id`	INT(10) UNSIGNED NOT NULL ,
	`type_id`		INT(10) UNSIGNED NOT NULL ,
	PRIMARY KEY (`billboard_id`, `type_id`) ,
	INDEX `FK_AYALINE_BILLBOARD_TYPE_INDEX_BILLBOARD_ID` (`billboard_id` ASC) ,
	INDEX `FK_AYALINE_BILLBOARD_TYPE_INDEX_TYPE_ID` (`type_id` ASC) ,
	CONSTRAINT `FK_AYALINE_BILLBOARD_TYPE_INDEX_BILLBOARD_ID`
		FOREIGN KEY (`billboard_id`)
		REFERENCES `{$this->getTable('ayalinebillboard/billboard')}` (`billboard_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `FK_AYALINE_BILLBOARD_TYPE_INDEX_TYPE_ID`
		FOREIGN KEY (`type_id`)
		REFERENCES `{$this->getTable('ayalinebillboard/billboard_type')}` (`type_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


CREATE  TABLE IF NOT EXISTS `{$this->getTable('ayalinebillboard/billboard_category')}` (
	`billboard_id`	INT(10)	UNSIGNED 	NOT NULL ,
	`category_id`	INT(10)	UNSIGNED 	NOT NULL ,
	`position`		INT(11) 			NOT NULL DEFAULT 0 ,
	PRIMARY KEY (`billboard_id`, `category_id`) ,
	INDEX `FK_AYALINE_BILLBOARD_CATEGORY_CATEGORY_ID` (`category_id` ASC) ,
	INDEX `FK_AYALINE_BILLBOARD_CATEGORY_BILLBOARD_ID` (`billboard_id` ASC) ,
	CONSTRAINT `FK_AYALINE_BILLBOARD_CATEGORY_CATEGORY_ID`
		FOREIGN KEY (`category_id`)
		REFERENCES `{$this->getTable('catalog/category')}` (`entity_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `FK_AYALINE_BILLBOARD_CATEGORY_BILLBOARD_ID`
		FOREIGN KEY (`billboard_id`)
		REFERENCES `{$this->getTable('ayalinebillboard/billboard')}` (`billboard_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

");

$this->endSetup();
