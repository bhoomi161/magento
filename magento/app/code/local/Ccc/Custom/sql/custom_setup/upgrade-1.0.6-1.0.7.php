<?php

$installer = $this;
$installer->startSetup();
$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('custom_media_gallery')}`;
    CREATE TABLE `{$this->getTable('custom_media_gallery')}` (
        `value_id` int(11) unsigned NOT NULL auto_increment,
        `entity_type_id` smallint(5) unsigned NOT NULL default '0',
        `attribute_id` smallint(5) unsigned NOT NULL default '0',
        `entity_id` int(10) unsigned NOT NULL default '0',
        `value` varchar(255) default NULL,
        PRIMARY KEY  (`value_id`),
        KEY `FK_CUSTOM_MEDIA_GALLERY_ATTRIBUTE` (`attribute_id`),
        KEY `FK_CUSTOM_MEDIA_GALLERY_ENTITY` (`entity_id`),
        KEY `FK_CUSTOM_MEDIA_GALLERY_ENTITY_TYPE` (`entity_type_id`),
        CONSTRAINT `FK_CUSTOM_MEDIA_GALLERY_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE,
        CONSTRAINT `FK_CUSTOM_MEDIA_GALLERY_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('custom')}` (`entity_id`) ON DELETE CASCADE,
        CONSTRAINT `FK_CUSTOM_MEDIA_GALLERY_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS `{$this->getTable('custom_media_gallery_value')}`;
    CREATE TABLE `{$this->getTable('custom_media_gallery_value')}` (
      `value_id` int(11) unsigned NOT NULL default '0',
      `store_id` smallint(5) unsigned NOT NULL default '0',
      `label` varchar(255) default NULL,
      `position` int(11) unsigned default NULL,
      `disabled` tinyint(1) unsigned NOT NULL default '0',
      PRIMARY KEY  (`value_id`,`store_id`),
      KEY `FK_CUSTOM_MEDIA_GALLERY_VALUE_STORE` (`store_id`),
      CONSTRAINT `FK_CUSTOM_MEDIA_GALLERY_VALUE_GALLERY` FOREIGN KEY (`value_id`) REFERENCES `{$this->getTable('custom_media_gallery')}` (`value_id`) ON DELETE CASCADE,
      CONSTRAINT `FK_CUSTOM_MEDIA_GALLERY_VALUE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
