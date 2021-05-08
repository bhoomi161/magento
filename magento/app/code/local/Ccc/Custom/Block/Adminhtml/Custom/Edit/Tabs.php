<?php

class Ccc_Custom_Block_Adminhtml_Custom_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_attributeTabBlock = 'custom/adminhtml_custom_edit_tab_attributes';


    public function __construct()
    {
      parent::__construct();
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('custom')->__('Custom Information'));
    }

    public function getCustom()
    {
        if (!($this->getData('custom') instanceof Ccc_Custom_Model_Custom)) {
            $this->setData('custom', Mage::registry('custom'));
        }
        return $this->getData('custom');
    }

    protected function _beforeToHtml()
    {
        $custom = $this->getCustom();

        if (!($setId = $custom->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }

        if ($setId) {
            $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();
            
        $customAttributes = Mage::getResourceModel('custom/custom_attribute_collection')->setAttributeSetFilter($setId)->load();

        if (!$this->getCustom()->getId()) {
            foreach ($customAttributes as $attribute) {
                $default = $attribute->getDefaultValue();
                if ($default != '') {
                    $this->getCustom()->setData($attribute->getAttributeCode(), $default);
                }
            }
        }        
        

       $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }

        }	
        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($customAttributes as $attribute) {
               
                if ($this->getCustom()->checkInGroup($attribute->getId(),$setId,$group->getId())) {
                   $attributes[] = $attribute;
                }
            }
           
            foreach ($attributes as $key => $attribute) {
                if( !$attribute->getIsVisible() ) {
                    unset($attributes[$key]);
                }
            }

            if (count($attributes)==0) {
                continue;
            }
            $active = $defaultGroupId == $group->getId();
                
            $this->addTab('group_' . $group->getId(), array(
                'label'     => Mage::helper('custom')->__($group->getAttributeGroupName()),
                'content'   =>  $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock(),'custom.adminhtml.custom.edit.tab.attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                ->toHtml()),
                'active' =>$active
            ));
            
        }
    }
        else{
            $this->addTab('set', array(
                'label'     => Mage::helper('custom')->__('Settings'),
                'content'   => $this->_translateHtml($this->getLayout()
                    ->createBlock('custom/Adminhtml_custom_edit_tab_settings')->toHtml()),
                'active'    => true
            ));
        }
      return parent::_beforeToHtml();
    }

    public function getAttributeTabBlock()
    {
        if (is_null(Mage::helper('custom/adminhtml_custom')->getAttributeTabBlock())) {
            return  $this->_attributeTabBlock;
        }
        return Mage::helper('custom/adminhtml_custom')->getAttributeTabBlock();
    }

    public function setAttributeTabBlock($attributeTabBlock)
    {
        $this->_attributeTabBlock = $attributeTabBlock;
        return $this;
    }
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
