<?php

// class Ccc_Custom_Model_Attribute_Frontend_Image extends Mage_Eav_Model_Entity_Attribute_Frontend_Abstract
// {

//     public function getUrl($object, $size=null)
//     {
//         $url = false;
//         $image = $object->getData($this->getAttribute()->getAttributeCode());

//         if( !is_null($size) && file_exists(Mage::getBaseDir('media').DS.'custom'.DS.$size.DS.$image) ) {
//             # resized image is cached
//             $url = Mage::app()->getStore($object->getStore())->getBaseUrl('media').'custom/' . $size . '/' . $image;
//         } elseif( !is_null($size) ) {
//             # resized image is not cached
//             $url = Mage::app()->getStore($object->getStore())->getBaseUrl().'custom/image/size/' . $size . '/' . $image;
//         } elseif ($image) {
//             # using original image
//             $url = Mage::app()->getStore($object->getStore())->getBaseUrl('media').'custom/'.$image;
//         }
//         return $url;
//     }

// }
