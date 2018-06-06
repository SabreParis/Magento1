<?php
/**
 * created : 10/03/2011
 *
 * @category  Ayaline
 * @package   Ayaline_Shop
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */


$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/shop_group')};
CREATE TABLE {$this->getTable('ayalineshop/shop_group')} (
  group_id int(10) unsigned NOT NULL auto_increment,
  name VARCHAR (25),
  icon varchar(255),
  PRIMARY KEY  (group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enseignes';

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/shop')};
CREATE TABLE {$this->getTable('ayalineshop/shop')} (
  shop_id int(10) unsigned NOT NULL auto_increment,
  group_id int(10) unsigned,
  updated_active_at datetime NOT NULL default '0000-00-00 00:00:00',
  is_active tinyint(1) unsigned NOT NULL default '1',
  title varchar(50),
  description text,
  street1 varchar(50),
  street2 varchar(50),
  postcode varchar(10),
  city varchar(100),
  country_id varchar(10),
  telephone varchar(25),
  fax varchar(25),
  email varchar(50),
  picture varchar(255),
  latitude varchar(25),
  longitude varchar(25),
  marker varchar(255),
  PRIMARY KEY  (shop_id),
  CONSTRAINT `FK_shop_shop_group` FOREIGN KEY (`group_id`) REFERENCES {$this->getTable('ayalineshop/shop_group')} (`group_id`)
) DEFAULT CHARSET=utf8 COMMENT='Magasins / Points de ventes';

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/shop_store')};
CREATE TABLE {$this->getTable('ayalineshop/shop_store')} (
  shop_id int(10) unsigned NOT NULL,
  store_id smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (shop_id, store_id),
  CONSTRAINT `FK_shop_store_shop_id` FOREIGN KEY (`shop_id`) REFERENCES {$this->getTable('ayalineshop/shop')} (`shop_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_shop_store_store_id` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE
) DEFAULT CHARSET=utf8 COMMENT='Liens magasins / stores';

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/schedules')};
CREATE TABLE {$this->getTable('ayalineshop/schedules')} (
  schedules_id int(10) unsigned NOT NULL auto_increment,
  day VARCHAR(25),
  hour_start_pm varchar(5), 
  hour_end_pm varchar(5),
  hour_start_am varchar(5),
  hour_end_am varchar(5),
  shop_id int(10) unsigned,
  sort int(10) NOT NULL default '0',
  PRIMARY KEY (schedules_id),
  CONSTRAINT `FK_AYALINE_SHOP_SCHEDULES` FOREIGN KEY (`shop_id`) REFERENCES {$this->getTable('ayalineshop/shop')} (`shop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARSET=utf8 COMMENT='Horaires magasins';

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/special_schedules')};
CREATE TABLE {$this->getTable('ayalineshop/special_schedules')} (
  schedules_id int(10) unsigned NOT NULL auto_increment,
  day VARCHAR(25),
  hour_start_pm varchar(5), 
  hour_end_pm varchar(5),
  hour_start_am varchar(5),
  hour_end_am varchar(5),
  shop_id int(10) unsigned,
  sort int(10) NOT NULL default '0',
  PRIMARY KEY  (schedules_id),
  CONSTRAINT `FK_AYALINE_SHOP_SPECIAL_SCHEDULES` FOREIGN KEY (`shop_id`) REFERENCES {$this->getTable('ayalineshop/shop')} (`shop_id`) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARSET=utf8 COMMENT='Horaires Spéciaux magasins';

DROP TABLE IF EXISTS {$this->getTable('ayalineshop/postcodes')};
CREATE TABLE {$this->getTable('ayalineshop/postcodes')} (
  postcodes_id int(10) unsigned NOT NULL auto_increment,
  shop_id int(10) unsigned,
  postcode varchar(25),
  PRIMARY KEY  (postcodes_id),
  CONSTRAINT `FK_shop_postcodes_id` FOREIGN KEY (`shop_id`) REFERENCES {$this->getTable('ayalineshop/shop')} (`shop_id`)
) DEFAULT CHARSET=utf8 COMMENT='Zones de chalandises';

");

$installer->endSetup();