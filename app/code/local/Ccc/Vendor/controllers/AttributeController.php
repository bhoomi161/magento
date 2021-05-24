<?php

class Ccc_Vendor_AttributeController extends Mage_Core_Controller_Front_Action{
    public function _initAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
    }

    public function preDispatch()
    {
        //$this->_setForcedFormKeyActions('delete');
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Resource_Product::ENTITY)->getTypeId();
    }

    protected function _getSession()
    {
       return Mage::getSingleton('vendor/session');
    }

    public function gridAction()
    {
       if(!$this->_getSession()->isLoggedIn()){
           $this->_redirect('vendor/account/login');
       }
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
  
    }

    public function newAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _filterPostData($data)
    {
        if ($data) {
            $helper = Mage::helper('vendor');
            foreach ($data['frontend_label'] as & $value) {
                if ($value) {
                    $value = $helper->stripTags($value);
                }
            }

            if (!empty($data['option']) && !empty($data['option']['value']) && is_array($data['option']['value'])) {
                $allowableTags = isset($data['is_html_allowed_on_front']) && $data['is_html_allowed_on_front']
                    ? sprintf('<%s>', implode('><', $this->_getAllowedTags())) : null;
                foreach ($data['option']['value'] as $key => $values) {
                    foreach ($values as $storeId => $storeLabel) {
                        $data['option']['value'][$key][$storeId]
                            = $helper->stripTags($storeLabel, $allowableTags);
                    }
                }
            }
        }
        return $data;
    }
    public function editAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
        
        $id = $this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('vendor/resource_eav_productattribute')
            ->setEntityTypeId($this->_entityTypeId);
        if ($id) {
            $model->load($id);

            if (! $model->getId()) {
                Mage::getSingleton('vendor/session')->addError(
                    Mage::helper('vendor')->__('This attribute no longer exists'));
                $this->_redirect('*/*/');
                return;
            }

            // entity type check
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('vendor/session')->addError(
                    Mage::helper('vendor')->__('This attribute cannot be edited.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = Mage::getSingleton('vendor/session')->getAttributeData(true);
        if (! empty($data)) {
            $model->addData($data);
        }

        Mage::register('entity_attribute', $model);

        $this->_initAction();

        $this->_title($id ? $model->getName() : $this->__('New Attribute'));

        $this->getLayout()->getBlock('attribute_edit_js');
        
    }
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        
        $default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
        ->getAttributeSetId('vendor_product', 'Default');

        $vendorId = Mage::getModel('vendor/session')->getId();
        if($data){

        $session = Mage::getSingleton('vendor/session');
        $model = Mage::getModel('vendor/resource_eav_productattribute');
        $helper = Mage::helper('vendor/vendor');

        $id = $this->getRequest()->getParam('attribute_id');
       
        $data['attribute_code']= $data['frontend_label'].'_'.$vendorId;
       
        //validate attribute_code
        if (isset($data['attribute_code'])) {
            $validatorAttrCode = new Zend_Validate_Regex(array('pattern' => '/^(?!event$)[a-z][a-z_0-9]{1,254}$/'));
            if (!$validatorAttrCode->isValid($data['attribute_code'])) {
                $session->addError(
                    Mage::helper('vendor')->__('Attribute code is invalid. Please use only letters (a-z), numbers (0-9) or underscore(_) in this field, first character should be a letter. Do not use "event" for an attribute code.')
                );
               // $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                return;
            }
        }

        //validate frontend_input
        if (isset($data['frontend_input'])) {
            /** @var $validatorInputType Mage_Eav_Model_Adminhtml_System_Config_Source_Inputtype_Validator */
            $validatorInputType = Mage::getModel('eav/adminhtml_system_config_source_inputtype_validator');
            if (!$validatorInputType->isValid($data['frontend_input'])) {
                foreach ($validatorInputType->getMessages() as $message) {
                    $session->addError($message);
                }
                $this->_redirect('*/*/edit', array('attribute_id' => $id, '_current' => true));
                return;
            }
        }

        if ($id) {
           
            $model->load($id);
            $model->setAttributeCode($data['frontend_label'].'_'.$vendorId);

            if(!$data['attribute_group_id']){
                $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
                $sql = "delete from eav_entity_attribute where attribute_id = $id";
                $conn->query($sql);
                
            }
            if (!$model->getId()) {
                $session->addError(
                    Mage::helper('vendor')->__('This Attribute no longer exists'));
                $this->_redirect('*/*/');
                return;
            }

            // entity type check
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                $session->addError(
                    Mage::helper('vendor')->__('This attribute cannot be updated.'));
                $session->setAttributeData($data);
                $this->_redirect('*/*/');
                return;
            }

            $data['backend_model'] = $model->getBackendModel();
            $data['attribute_code'] = $model->getAttributeCode();
            $data['is_user_defined'] = $model->getIsUserDefined();
            $data['frontend_input'] = $model->getFrontendInput();
        } else {
            /**
            * @todo add to helper and specify all relations for properties
            */
            $data['source_model'] = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
            $data['backend_model'] = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
        }

        // if (!isset($data['is_configurable'])) {
        //     $data['is_configurable'] = 0;
        // }
        // if (!isset($data['is_filterable'])) {
        //     $data['is_filterable'] = 0;
        // }
        // if (!isset($data['is_filterable_in_search'])) {
        //     $data['is_filterable_in_search'] = 0;
        // }

        if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
            $data['backend_type'] = $model->getBackendTypeByInput($data['frontend_input']);
        }

        $defaultValueField = $model->getDefaultValueByInput($data['frontend_input']);
        if ($defaultValueField) {
            $data['default_value'] = $this->getRequest()->getParam($defaultValueField);
        }

        if(!isset($data['apply_to'])) {
            $data['apply_to'] = array();
        }
      
        //filter
        $data = $this->_filterPostData($data);
        $model->addData($data);
    
        if (!$id) {
            $model->setEntityTypeId($this->_entityTypeId);
            $model->setIsUserDefined(1);
        }

        // if ($this->getRequest()->getParam('set') && $this->getRequest()->getParam('group')) {
        //     // For creating product attribute on product page we need specify attribute set and group
        //     $model->setAttributeSetId($this->getRequest()->getParam('set'));
        //     $model->setAttributeGroupId($this->getRequest()->getParam('group'));
        // }

        try {
            $model->save();
            $attributeId = $model->getId();
            if($data['group']){

                $groupModel = Mage::getModel('eav/entity_attribute');
                $groupModel->setEntityTypeId($this->_entityTypeId)
                ->setAttributeSetId($default_attribute_set_id)
                ->setAttributeGroupId($data['group'])
                ->setAttributeId($attributeId);
                $groupModel->save();
               
            }
            
            $session->addSuccess(
                Mage::helper('vendor')->__('The vendor attribute has been saved.'));

            Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
            $session->setAttributeData(false);
            $this->_redirect('*/*/grid', array());
            return;
        } catch (Exception $e) {
            $session->addError($e->getMessage());
            $session->setAttributeData($data);
            $this->_redirect('*/attribute/grid', array('attribute_id' => $id, '_current' => true));
            return;
        }
    }
    $this->_redirect('*/*/grid');     
    }
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            $model = Mage::getModel('vendor/resource_eav_productattribute');

            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('vendor/session')->addError(
                    Mage::helper('vendor')->__('This attribute cannot be deleted.'));
                $this->_redirect('*/*/');
                return;
            }

            try {
                $model->delete();
                Mage::getSingleton('vendor/session')->addSuccess(
                    Mage::helper('vendor')->__('The product attribute has been deleted.'));
                $this->_redirect('*/*/grid');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('vendor/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
                return;
            }
        }
        Mage::getSingleton('vendor/session')->addError(
            Mage::helper('vendor')->__('Unable to find an attribute to delete.'));
        $this->_redirect('*/*/grid');
    
    }

}