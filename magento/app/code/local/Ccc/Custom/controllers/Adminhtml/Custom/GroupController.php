<?php

class Ccc_Custom_Adminhtml_Custom_GroupController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('custom/custom');
    }
    
    // public function saveAction()
    // {
    //     echo 'Hello';
    //     die;
    //     $model = Mage::getModel('eav/entity_attribute_group');

    //     $model->setAttributeGroupName($this->getRequest()->getParam('attribute_group_name'))
    //           ->setAttributeSetId($this->getRequest()->getParam('attribute_set_id'));

    //     if( $model->itemExists() ) {
    //         Mage::getSingleton('custom/session')->addError(Mage::helper('custom')->__('A group with the same name already exists.'));
    //     } else {
    //         try {
    //             $model->save();
    //         } catch (Exception $e) {
    //             Mage::getSingleton('custom/session')->addError(Mage::helper('custom')->__('An error occurred while saving this group.'));
    //         }
    //     }
    // }

}