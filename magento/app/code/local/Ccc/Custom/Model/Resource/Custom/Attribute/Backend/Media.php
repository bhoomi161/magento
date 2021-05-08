<?php
// class Ccc_Custom_Model_Resource_Custom_Attribute_Backend_Media extends Mage_Core_Model_Resource_Db_Abstract
// {
//     const GALLERY_TABLE       = 'custom_media_gallery';
//     const GALLERY_VALUE_TABLE = 'custom_media_gallery_value';

//     protected $_eventPrefix = 'custom/custom_attribute_backend_media';

//     private $_attributeId = null;
    
//     protected function _construct()
//     {
//         $this->_init(self::GALLERY_TABLE, 'value_id');
//     }

//     public function loadGallery($custom, $object)
//     {
        
//         $eventObjectWrapper = new Varien_Object(
//             array(
//                 'custom' => $custom,
//                 'backend_attribute' => $object
//             )
//         );
      
//         Mage::dispatchEvent(
//             $this->_eventPrefix . '_load_gallery_before',
//             array('event_object_wrapper' => $eventObjectWrapper)
//         );
        
//         if ($eventObjectWrapper->hasCustomIdsOverride()) {
//             $customIds = $eventObjectWrapper->getCustomIdsOverride();
//         } else {
//             $customIds = array($custom->getId());
//         }
        
//         $select = $this->_getLoadGallerySelect($customIds, $custom->getStoreId(), $object->getAttribute()->getId());
       
//         $adapter = $this->_getReadAdapter();
//         $result = $adapter->fetchAll($select);
//         $this->_removeDuplicates($result);
//         return $result;
//     }
//     protected function _removeDuplicates(&$result)
//     {
//         $fileToId = array();

//         foreach (array_keys($result) as $index) {
//             if (!isset($fileToId[$result[$index]['file']])) {
//                 $fileToId[$result[$index]['file']] = $result[$index]['value_id'];
//             } elseif ($fileToId[$result[$index]['file']] != $result[$index]['value_id']) {
//                 $this->deleteGallery($result[$index]['value_id']);
//                 unset($result[$index]);
//             }
//         }

//         $result = array_values($result);
//         return $this;
//     }

//     public function insertGallery($data)
//     {
//         $adapter = $this->_getWriteAdapter();
//         $data    = $this->_prepareDataForTable(new Varien_Object($data), $this->getMainTable());
//         $adapter->insert($this->getMainTable(), $data);

//         return $adapter->lastInsertId($this->getMainTable());
//     }

   
//     public function deleteGallery($valueId)
//     {
//         if (is_array($valueId) && count($valueId)>0) {
//             $condition = $this->_getWriteAdapter()->quoteInto('value_id IN(?) ', $valueId);
//         } elseif (!is_array($valueId)) {
//             $condition = $this->_getWriteAdapter()->quoteInto('value_id = ? ', $valueId);
//         } else {
//             return $this;
//         }

//         $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
//         return $this;
//     }
//     public function insertGalleryValueInStore($data)
//     {
//         $data = $this->_prepareDataForTable(new Varien_Object($data), $this->getTable(self::GALLERY_VALUE_TABLE));
//         $this->_getWriteAdapter()->insert($this->getTable(self::GALLERY_VALUE_TABLE), $data);

//         return $this;
//     }

//     public function deleteGalleryValueInStore($valueId, $storeId)
//     {
//         $adapter = $this->_getWriteAdapter();

//         $conditions = implode(' AND ', array(
//             $adapter->quoteInto('value_id = ?', (int) $valueId),
//             $adapter->quoteInto('store_id = ?', (int) $storeId),
//         ));

//         $adapter->delete($this->getTable(self::GALLERY_VALUE_TABLE), $conditions);

//         return $this;
//     }

//     public function duplicate($object, $newFiles, $originalCustomId, $newCustomId)
//     {
//         $select = $this->_getReadAdapter()->select()
//             ->from($this->getMainTable(), array('value_id', 'value'))
//             ->where('attribute_id = ?', $object->getAttribute()->getId())
//             ->where('entity_id = ?', $originalCustomId);

//         $valueIdMap = array();
//         // Duplicate main entries of gallery
//         foreach ($this->_getReadAdapter()->fetchAll($select) as $row) {
//             $data = array(
//                 'attribute_id' => $object->getAttribute()->getId(),
//                 'entity_id'    => $newCustomId,
//                 'value'        => (isset($newFiles[$row['value_id']]) ? $newFiles[$row['value_id']] : $row['value'])
//             );

//             $valueIdMap[$row['value_id']] = $this->insertGallery($data);
//         }

//         if (count($valueIdMap) == 0) {
//             return $this;
//         }

//         // Duplicate per store gallery values
//         $select = $this->_getReadAdapter()->select()
//             ->from($this->getTable(self::GALLERY_VALUE_TABLE))
//             ->where('value_id IN(?)', array_keys($valueIdMap));

//         foreach ($this->_getReadAdapter()->fetchAll($select) as $row) {
//             $row['value_id'] = $valueIdMap[$row['value_id']];
//             $this->insertGalleryValueInStore($row);
//         }

//         return $this;
//     }

//     protected function _getLoadGallerySelect(array $customIds, $storeId, $attributeId) {
        
//         $adapter = $this->_getReadAdapter();
//         $positionCheckSql = $adapter->getCheckSql('value.position IS NULL', 'default_value.position', 'value.position');

//         // Select gallery images for product
//         $select = $adapter->select()
//             ->from(
//                 array('main'=>$this->getMainTable()),
//                 array('value_id', 'value AS file', 'custom_id' => 'entity_id')
//             )
//             ->joinLeft(
//                 array('value' => $this->getTable(self::GALLERY_VALUE_TABLE)),
//                 $adapter->quoteInto('main.value_id = value.value_id AND value.store_id = ?', (int)$storeId),
//                 array('label','position','disabled')
//             )
//             ->joinLeft( // Joining default values
//                 array('default_value' => $this->getTable(self::GALLERY_VALUE_TABLE)),
//                 'main.value_id = default_value.value_id AND default_value.store_id = 0',
//                 array(
//                     'label_default' => 'label',
//                     'position_default' => 'position',
//                     'disabled_default' => 'disabled'
//                 )
//             )
//             ->where('main.attribute_id = ?', $attributeId)
//             ->where('main.entity_id in (?)', $customIds)
//             ->order($positionCheckSql . ' ' . Varien_Db_Select::SQL_ASC);

//         return $select;
//     }
//     protected function _getAttributeId() {
//         if(is_null($this->_attributeId)) {
//             $attribute = Mage::getModel('eav/entity_attribute')
//                 ->loadByCode(Ccc_Custom_Model_Custom::ENTITY, 'media_gallery');

//             $this->_attributeId = $attribute->getId();
//         }
//         return $this->_attributeId;
//     }
//     public function loadGallerySet(array $customIds, $storeId) {
//         $select = $this->_getLoadGallerySelect($customIds, $storeId, $this->_getAttributeId());

//         $adapter = $this->_getReadAdapter();
//         $result = $adapter->fetchAll($select);
//         $this->_removeDuplicates($result);
//         return $result;
//     }
//}
