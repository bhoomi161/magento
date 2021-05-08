<?php

class Ccc_Process_Block_Adminhtml_Process_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
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

        $fieldset = $form->addFieldset('process_form',array('legend'=>Mage::helper('process')->__('process Information')));
        
        $fieldset->addField('process_type_id','text',array(
            'label'=>Mage::helper('process')->__('Process Type Id'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'process_type_id'
        ));

        $fieldset->addField('group_id','text',array(
            'label'=>Mage::helper('process')->__('Group Id'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'group_id'));

        $fieldset->addField('name','text',array(
             'label'=>Mage::helper('process')->__('Name'),
             'class'=>'required-entry',
            'required'=>true,
            'name'=>'name'));
        
        $fieldset->addField('per_request_count','text',array(
            'label'=>Mage::helper('process')->__('Per Request Count'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'per_request_count'));

        $fieldset->addField('request_interval','text',array(
                'label'=>Mage::helper('process')->__('Request Interval'),
                'class'=>'required-entry',
                'required'=>true,
                'name'=>'request_interval'));

        $fieldset->addField('model','select',array(
                'label'=>Mage::helper('process')->__('Model'),
                'class'=>'required-entry',
                'required'=>true,
                'name'=>'model',
                'values'=>array(
                    array(
                        'value'=>'verification',
                        'label'=>Mage::helper('process')->__('Verification'),
                    ),
                    array(
                        'value'=>'import',
                        'label'=>Mage::helper('process')->__('Import'),
                    ),
               
        )));
    
        if(Mage::getSingleton('adminhtml/session')->getprocessData()){
                $form->setValues(Mage::getSingleton('adminhtml/session')->getProcessData());
                Mage::getSingleton('adminhtml/session')->setprocessData(null);
        }
        elseif(Mage::registry('process_data')){
                $form->setValues(Mage::registry('process_data')->getData());
        }
        return parent::_prepareForm();
    }
}