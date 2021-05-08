<?php

class Ccc_Custom_Block_Adminhtml_Custom_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{

    public function getCustom()
    {
        return Mage::registry('current_custom');
    }

    protected function _prepareLayout()
    {
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
            parent::_prepareLayout();
           
    }
    protected function _prepareForm()
    {
        $group = $this->getGroup();

        $attributes = $this->getAttributes();
        // echo '<pre>';
        // print_r($attributes);
        // die;
       
        $form = new Varien_Data_Form();
       // $this->setForm($form);

        $form->setDataObject($this->getCustom());


        $form->setHtmlIdPrefix('group_' . $group->getId());
        $form->setFieldNameSuffix('account');
        $fieldset = $form->addFieldset('fieldset_group_' . $group->getId(), array(
            'legend'    => Mage::helper('custom')->__($group->getAttributeGroupName()),
            'class'     => 'fieldset',
        ));

    //     if (!$form->getElement('media_gallery')
    //     && Mage::getSingleton('admin/session')->isAllowed('catalog/attributes/attributes')
    //      ) {
    //     $headerBar = $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_attributes_create');

    //     $headerBar->getConfig()
    //         ->setTabId('group_' . $group->getId())
    //         ->setGroupId($group->getId())
    //         ->setStoreId($form->getDataObject()->getStoreId())
    //         ->setAttributeSetId($form->getDataObject()->getAttributeSetId())
    //         ->setTypeId($form->getDataObject()->getTypeId())
    //         ->setProductId($form->getDataObject()->getId());

    //     $fieldset->setHeaderBar($headerBar->toHtml());
    // }
        $this->_setFieldset($attributes,$fieldset);
        
        $form->addValues($this->getCustom()->getData());
        Mage::dispatchEvent('adminhtml_custom_edit_prepare_form', array('form' => $form));

        $this->setForm($form);
       // return parent::_prepareForm();
    }
    protected function _getAdditionalElementTypes()
    {
        $result = array(
            'price'    => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_price'),
            'weight'   => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_weight'),
            'gallery'  => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_gallery'),
            'image'    => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_image'),
            'boolean'  => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_boolean'),
            'textarea' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_helper_form_wysiwyg')
        );

        $response = new Varien_Object();
        $response->setTypes(array());
        Mage::dispatchEvent('adminhtml_catalog_product_edit_element_types', array('response' => $response));

        foreach ($response->getTypes() as $typeName => $typeClass) {
            $result[$typeName] = $typeClass;
        }

        return $result;
    }


}
