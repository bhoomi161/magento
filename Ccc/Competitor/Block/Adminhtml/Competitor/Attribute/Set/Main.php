<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Attribute_Set_Main extends Mage_Adminhtml_Block_Template
{
    /**
     * Initialize template
     *
     */
    protected function _construct()
    {
        $this->setTemplate('catalog/product/attribute/set/main.phtml');
    }

    /**
     * Prepare Global Layout
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Attribute_Set_Main
     */
    protected function _prepareLayout()
    {
        $setId = $this->_getSetId();

        $this->setChild('group_tree',
            $this->getLayout()->createBlock('competitor/adminhtml_competitor_attribute_set_main_tree_group')
        );

        $this->setChild('edit_set_form',
            $this->getLayout()->createBlock('competitor/adminhtml_competitor_attribute_set_main_formset')
        );

        $this->setChild('delete_group_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('Delete Selected Group'),
                'onclick'   => 'editSet.submit();',
                'class'     => 'delete'
        )));

        $this->setChild('add_group_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('Add New'),
                'onclick'   => 'editSet.addGroup();',
                'class'     => 'add'
        )));

        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('Back'),
                'onclick'   => 'setLocation(\''.$this->getUrl('*/*/').'\')',
                'class'     => 'back'
        )));

        $this->setChild('reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('Reset'),
                'onclick'   => 'window.location.reload()'
        )));

        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('Save Attribute Set'),
                'onclick'   => 'editSet.save();',
                'class'     => 'save'
        )));

        $deleteConfirmMessage = $this->jsQuoteEscape(Mage::helper('custom')
            ->__('All products of this set will be deleted! Are you sure you want to delete this attribute set?'));
        $deleteUrl = $this->getUrl('*/*/delete', array('id' => $setId));
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('custom')->__('Delete Attribute Set'),
                'onclick'   => 'deleteConfirm(\'' . $deleteConfirmMessage . '\', \'' . $deleteUrl . '\')',
                'class'     => 'delete'
        )));

        $this->setChild('rename_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('competitor')->__('New Set Name'),
                'onclick'   => 'editSet.rename()'
        )));

        return parent::_prepareLayout();
    }

    /**
     * Retrieve Attribute Set Group Tree HTML
     *
     * @return string
     */
    public function getGroupTreeHtml()
    {
        return $this->getChildHtml('group_tree');
    }

    /**
     * Retrieve Attribute Set Edit Form HTML
     *
     * @return string
     */
    public function getSetFormHtml()
    {
        return $this->getChildHtml('edit_set_form');
    }

    /**
     * Retrieve Block Header Text
     *
     * @return string
     */
    protected function _getHeader()
    {
        return Mage::helper('competitor')->__("Edit Attribute Set '%s'", $this->_getAttributeSet()->getAttributeSetName());
    }

    /**
     * Retrieve Attribute Set Save URL
     *
     * @return string
     */
    public function getMoveUrl()
    {
        return $this->getUrl('*/competitor_set/save', array('id' => $this->_getSetId()));
    }

    /**
     * Retrieve Attribute Set Group Save URL
     *
     * @return string
     */
    public function getGroupUrl()
    {
        return $this->getUrl('*/competitor_group/save', array('id' => $this->_getSetId()));
    }

    /**
     * Retrieve Attribute Set Group Tree as JSON format
     *
     * @return string
     */
    public function getGroupTreeJson()
    {
        $items = array();
        $setId = $this->_getSetId();

        /* @var $groups Mage_Eav_Model_Mysql4_Entity_Attribute_Group_Collection */
        $groups = Mage::getModel('eav/entity_attribute_group')
            ->getResourceCollection()
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();

        /* @var $node Mage_Eav_Model_Entity_Attribute_Group */
        foreach ($groups as $node) {
            $item = array();
            $item['text']       = $node->getAttributeGroupName();
            $item['id']         = $node->getAttributeGroupId();
            $item['cls']        = 'folder';
            $item['allowDrop']  = true;
            $item['allowDrag']  = true;

            $nodeChildren = Mage::getResourceModel('competitor/competitor_attribute_collection')
                ->setAttributeGroupFilter($node->getId())
                ->checkConfigurableProducts()
                ->load();

            if ($nodeChildren->getSize() > 0) {
                $item['children'] = array();
                foreach ($nodeChildren->getItems() as $child) {
                    /* @var $child Mage_Eav_Model_Entity_Attribute */
                    $attr = array(
                        'text'              => $child->getAttributeCode(),
                        'id'                => $child->getAttributeId(),
                        'cls'               => (!$child->getIsUserDefined()) ? 'system-leaf' : 'leaf',
                        'allowDrop'         => false,
                        'allowDrag'         => true,
                        'leaf'              => true,
                        'is_user_defined'   => $child->getIsUserDefined(),
                        'entity_id'         => $child->getEntityAttributeId()
                    );

                    $item['children'][] = $attr;
                }
            }

            $items[] = $item;
        }

        return Mage::helper('core')->jsonEncode($items);
    }

    /**
     * Retrieve Unused in Attribute Set Attribute Tree as JSON
     *
     * @return string
     */
    public function getAttributeTreeJson()
    {
        $items = array();
        $setId = $this->_getSetId();

        $collection = Mage::getResourceModel('competitor/competitor_attribute_collection')
            ->setAttributeSetFilter($setId)
            ->load();

        $attributesIds = array('0');
        /* @var $item Mage_Eav_Model_Entity_Attribute */
        foreach ($collection->getItems() as $item) {
            $attributesIds[] = $item->getAttributeId();
        }

        $attributes = Mage::getResourceModel('competitor/competitor_attribute_collection')
            ->setAttributesExcludeFilter($attributesIds)
            ->load();

        foreach ($attributes as $child) {
            $attr = array(
                'text'              => $child->getAttributeCode(),
                'id'                => $child->getAttributeId(),
                'cls'               => 'leaf',
                'allowDrop'         => false,
                'allowDrag'         => true,
                'leaf'              => true,
                'is_user_defined'   => $child->getIsUserDefined(),
                'is_configurable'   => false,
                'entity_id'         => $child->getEntityId()
            );

            $items[] = $attr;
        }

        if (count($items) == 0) {
            $items[] = array(
                'text'      => Mage::helper('competitor')->__('Empty'),
                'id'        => 'empty',
                'cls'       => 'folder',
                'allowDrop' => false,
                'allowDrag' => false,
            );
        }

        return Mage::helper('core')->jsonEncode($items);
    }

    /**
     * Retrieve Back Button HTML
     *
     * @return string
     */
    public function getBackButtonHtml()
    {
        return $this->getChildHtml('back_button');
    }

    /**
     * Retrieve Reset Button HTML
     *
     * @return string
     */
    public function getResetButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    /**
     * Retrieve Save Button HTML
     *
     * @return string
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * Retrieve Delete Button HTML
     *
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        if ($this->getIsCurrentSetDefault()) {
            return '';
        }
        return $this->getChildHtml('delete_button');
    }

    /**
     * Retrieve Delete Group Button HTML
     *
     * @return string
     */
    public function getDeleteGroupButton()
    {
        return $this->getChildHtml('delete_group_button');
    }

    /**
     * Retrieve Add New Group Button HTML
     *
     * @return string
     */
    public function getAddGroupButton()
    {
        return $this->getChildHtml('add_group_button');
    }

    /**
     * Retrieve Rename Button HTML
     *
     * @return string
     */
    public function getRenameButton()
    {
        return $this->getChildHtml('rename_button');
    }

    /**
     * Retrieve current Attribute Set object
     *
     * @return Mage_Eav_Model_Entity_Attribute_Set
     */
    protected function _getAttributeSet()
    {
        return Mage::registry('current_attribute_set');
    }

    /**
     * Retrieve current attribute set Id
     *
     * @return int
     */
    protected function _getSetId()
    {
        return $this->_getAttributeSet()->getId();
    }

    /**
     * Check Current Attribute Set is a default
     *
     * @return bool
     */
    public function getIsCurrentSetDefault()
    {
        $isDefault = $this->getData('is_current_set_default');
        if (is_null($isDefault)) {
            $defaultSetId = Mage::getModel('eav/entity_type')
                ->load(Mage::registry('entityType'))
                ->getDefaultAttributeSetId();
            $isDefault = $this->_getSetId() == $defaultSetId;
            $this->setData('is_current_set_default', $isDefault);
        }
        return $isDefault;
    }

    /**
     * Retrieve current Attribute Set object
     *
     * @deprecated use _getAttributeSet
     * @return Mage_Eav_Model_Entity_Attribute_Set
     */
    protected function _getSetData()
    {
        return $this->_getAttributeSet();
    }

    protected function _toHtml()
    {
        return parent::_toHtml();
    }
}
