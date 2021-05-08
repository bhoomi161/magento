<?php

class Ccc_Process_Block_Adminhtml_Process_Type_Column_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
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

        $fieldset = $form->addFieldset('process_type_column_form',array('legend'=>Mage::helper('process')->__('Process Type Column Information')));
            
        $fieldset->addField('name','text',array(
             'label'=>Mage::helper('process')->__('Name'),
             'class'=>'required-entry',
            'required'=>true,
            'name'=>'name'));
        
        $fieldset->addField('sample','text',array(
            'label'=>Mage::helper('process')->__('Sample'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'sample'));

        $fieldset->addField('default','text',array(
                'label'=>Mage::helper('process')->__('Default'),
                'class'=>'required-entry',
                'required'=>true,
                'name'=>'default'));

        $fieldset->addField('is_required','select',array(
                'label'=>Mage::helper('process')->__('Is Required'),
                'class'=>'required-entry',
                'required'=>true,
                'name'=>'is_required',
                'values'=>array(
                    array(
                        'value'=>1,
                        'label'=>Mage::helper('process')->__('Yes'),
                    ),
                    array(
                        'value'=>0,
                        'label'=>Mage::helper('process')->__('No'),
                    ),
            )));
               
        
        $fieldset->addField('backend_type','select',array(
            'label'=>Mage::helper('process')->__('Backend Type'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'backend_type',
            'values'=>array(
                array(
                    'value'=>'varchar',
                    'label'=>Mage::helper('process')->__('Varchar'),
                ),
                array(
                     'value'=>'text',
                    'label'=>Mage::helper('process')->__('Text'),
                ),
                array(
                    'value'=>'int',
                   'label'=>Mage::helper('process')->__('Integer'),
                ),
                array(
                    'value'=>'datetime',
                   'label'=>Mage::helper('process')->__('Date'),
               ),
        )));

        $fieldset->addField('sort_order','text',array(
                        'label'=>Mage::helper('process')->__('Sort Order'),
                        'class'=>'required-entry',
                        'required'=>true,
                        'name'=>'sort_order'));
    

            if(Mage::getSingleton('adminhtml/session')->getprocessData()){
                $form->setValues(Mage::getSingleton('adminhtml/session')->getProcessData());
                Mage::getSingleton('adminhtml/session')->setProcessTypeColumnData(null);
            }
            elseif(Mage::registry('process_type_column_data')){
                $form->setValues(Mage::registry('process_type_column_data')->getData());
    
            }
            return parent::_prepareForm();
        

        }
}