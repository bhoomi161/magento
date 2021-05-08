<?php

class Ccc_User_Block_Adminhtml_User_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
    protected function _prepareForm(){
        $form = new Varien_Data_Form(
            array(
                'id'=>'edit_form',
                'action'=>$this->getUrl('*/*/save',array('id'=>$this->getRequest()->getParam('id'))),
                'method'=>'post'
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('user_form',array('legend'=>Mage::helper('user')->__('User Information')));

        $fieldset->addField('first_name','text',array(
            'label'=>Mage::helper('user')->__('First Name'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'first_name'));

        $fieldset->addField('last_name','text',array(
            'label'=>Mage::helper('user')->__('Last Name'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'last_name'));

        $fieldset->addField('password','password',array(
            'label'=>Mage::helper('user')->__('Password'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'password'
        ));
        $fieldset->addField('mobile','text',array(
            'label'=>Mage::helper('user')->__('Mobile'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'mobile'));

        $fieldset->addField('email','text',array(
             'label'=>Mage::helper('user')->__('Email'),
             'class'=>'required-entry',
            'required'=>true,
            'name'=>'email'));

        $fieldset->addField('status','select',array(
            'label'=>Mage::helper('user')->__('Status'),
            'name'=>'status',
            'values'=>array(
                array(
                    'value'=>1,
                    'label'=>Mage::helper('user')->__('Active'),
                ),
                array(
                    'value'=>0,
                    'label'=>Mage::helper('user')->__('InActive'),
                ),
            )));

        
        if(Mage::getSingleton('adminhtml/session')->getUserData()){
            $form->setValues(Mage::getSingleton('adminhtml/session')->getUserData());
            Mage::getSingleton('adminhtml/session')->setUserData(null);
        }
        elseif(Mage::registry('user_data')){
            $form->setValues(Mage::registry('user_data')->getData());
        }
        return parent::_prepareForm();
    }
}
?>