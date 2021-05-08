<?php

class Ccc_Process_Block_Adminhtml_Process_Type_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
    protected function _prepareForm(){
        $form = new Varien_Data_Form(
            array(
                'id'=>'edit_form',
                'action'=>$this->getUrl('*/*/save',array('id'=>$this->getRequest()->getParam('id'))),
                'method'=>'post'
            )
        );

        $form->setUseContainer($form);
        $this->setForm($form);

        $fieldset = $form->addFieldset('process_type_form',array('legend'=>Mage::helper('process')->__('Process Type Information')));
        
      
        $fieldset->addField('name','text',array(
             'label'=>Mage::helper('process')->__('Name'),
             'class'=>'required-entry',
            'required'=>true,
            'name'=>'name'));
        
        
            if(Mage::getSingleton('adminhtml/session')->getprocessData()){
                $form->setValues(Mage::getSingleton('adminhtml/session')->getProcessTypeData());
                Mage::getSingleton('adminhtml/session')->setprocessData(null);
            }
            elseif(Mage::registry('process_type_data')){
                $form->setValues(Mage::registry('process_type_data')->getData());
    
            }
            return parent::_prepareForm();
        

        }
}