<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('custom_gallery')};
CREATE TABLE {$this->getTable('custom_gallery')} (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(5) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `position` int(11) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  KEY `IDX_BASE` USING BTREE (`entity_type_id`,`entity_id`,`attribute_id`,`store_id`),
  KEY `FK_ATTRIBUTE_GALLERY_ENTITY` (`entity_id`),
  KEY `FK_CUSTOM_GALLERY_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CUSTOM_ENTITY_GALLERY_STORE` (`store_id`),
  CONSTRAINT `FK_CUSTOM_GALLERY_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES {$this->getTable('eav_attribute')} (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CUSTOM_ENTITY_GALLERY_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES {$this->getTable('custom')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_CUSTOM_ENTITY_GALLERY_STORE` FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8; ");
$installer->endSetup();
?>