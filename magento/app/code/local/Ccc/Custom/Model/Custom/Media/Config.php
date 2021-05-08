<?php

// class Ccc_Custom_Model_Custom_Media_Config implements Mage_Media_Model_Image_Config_Interface
// {
    
//     public function getBaseMediaPathAddition()
//     {
//         return 'custom';
//     }

//     public function getBaseMediaUrlAddition()
//     {
//         return 'custom';
//     }

//     public function getBaseTmpMediaPathAddition()
//     {
//         return 'tmp' . DS . $this->getBaseMediaPathAddition();
//     }

   
//     public function getBaseTmpMediaUrlAddition()
//     {
//         return 'tmp/' . $this->getBaseMediaUrlAddition();
//     }

//     public function getBaseMediaPath()
//     {
//         return Mage::getBaseDir('media') . DS . 'custom';
//     }

//     public function getBaseMediaUrl()
//     {
//         return Mage::getBaseUrl('media') . 'custom';
//     }

//     public function getBaseTmpMediaPath()
//     {
//         return Mage::getBaseDir('media') . DS . $this->getBaseTmpMediaPathAddition();
//     }

//     public function getBaseTmpMediaUrl()
//     {
//         return Mage::getBaseUrl('media') . $this->getBaseTmpMediaUrlAddition();
//     }

//     public function getMediaUrl($file)
//     {
//         $file = $this->_prepareFileForUrl($file);

//         if(substr($file, 0, 1) == '/') {
//             return $this->getBaseMediaUrl() . $file;
//         }

//         return $this->getBaseMediaUrl() . '/' . $file;
//     }

//     public function getMediaPath($file)
//     {
//         $file = $this->_prepareFileForPath($file);

//         if(substr($file, 0, 1) == DS) {
//             return $this->getBaseMediaPath() . DS . substr($file, 1);
//         }

//         return $this->getBaseMediaPath() . DS . $file;
//     }

//     public function getTmpMediaUrl($file)
//     {
//         $file = $this->_prepareFileForUrl($file);

//         if(substr($file, 0, 1) == '/') {
//             $file = substr($file, 1);
//         }

//         return $this->getBaseTmpMediaUrl() . '/' . $file;
//     }

//     /**
//      * Part of URL of temporary product images
//      * relatively to media folder
//      *
//      * @return string
//      */
//     public function getTmpMediaShortUrl($file)
//     {
//         $file = $this->_prepareFileForUrl($file);

//         if(substr($file, 0, 1) == '/') {
//             $file = substr($file, 1);
//         }

//         return $this->getBaseTmpMediaUrlAddition() . '/' . $file;
//     }

//     /**
//      * Part of URL of product images relatively to media folder
//      *
//      * @return string
//      */
//     public function getMediaShortUrl($file)
//     {
//         $file = $this->_prepareFileForUrl($file);

//         if(substr($file, 0, 1) == '/') {
//             $file = substr($file, 1);
//         }

//         return $this->getBaseMediaUrlAddition() . '/' . $file;
//     }

//     public function getTmpMediaPath($file)
//     {
//         $file = $this->_prepareFileForPath($file);

//         if(substr($file, 0, 1) == DS) {
//             return $this->getBaseTmpMediaPath() . DS . substr($file, 1);
//         }

//         return $this->getBaseTmpMediaPath() . DS . $file;
//     }

//     protected function _prepareFileForUrl($file)
//     {
//         return str_replace(DS, '/', $file);
//     }

//     protected function _prepareFileForPath($file)
//     {
//         return str_replace('/', DS, $file);
//     }
// }
