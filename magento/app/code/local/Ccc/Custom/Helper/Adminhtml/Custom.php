<?php
class Ccc_Custom_Helper_Adminhtml_Custom extends Mage_Core_Helper_Abstract
{
    const XML_PATH_SITEMAP_VALID_PATHS = 'general/file/sitemap_generate_valid_paths';


    protected $_attributeTabBlock = null;

   
    protected $_categoryAttributeTabBlock;

  
    public function getAttributeTabBlock()
    {
        return $this->_attributeTabBlock;
    }

    public function setAttributeTabBlock($attributeTabBlock)
    {
        $this->_attributeTabBlock = $attributeTabBlock;
        return $this;
    }

    
    public function getCategoryAttributeTabBlock()
    {
        return $this->_categoryAttributeTabBlock;
    }

    
    public function setCategoryAttributeTabBlock($attributeTabBlock)
    {
        $this->_categoryAttributeTabBlock = $attributeTabBlock;
        return $this;
    }

    
    public function getSitemapValidPaths()
    {
        $path = Mage::getStoreConfig(self::XML_PATH_SITEMAP_VALID_PATHS);
        $helper = Mage::helper('core');
        $path = array_merge($path, $helper->getPublicFilesValidPath());
        return $path;
    }
}
