<?php

class Ccc_Vendor_MarketPlaceController extends Mage_Core_Controller_Front_Action{
    protected $_entityTypeId;
    const XML_PATH_ALLOWED_TAGS = 'system/catalog/frontend/allowed_html_tags_list';

    protected function _getAllowedTags()
    {
        return explode(',', Mage::getStoreConfig(self::XML_PATH_ALLOWED_TAGS));
    }

    public function formAction()
    {
       $this->loadLayout();
       $this->renderLayout();
    }
    public function getAssignedAttributeOfGroup()
    {
        $vendorId = Mage::getModel('vendor/session')->getId();
        $groupId = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('vendor/resource_product_attribute_collection')
        ->addFieldToFilter('attribute_code',array("like"=>'%'.$vendorId));
        $collection->getSelect()
            ->join(
                array('vendor_product_attribute'=>'eav_entity_attribute'),
                'vendor_product_attribute.attribute_id = main_table.attribute_id',
                array('*')
            );
            return $collection;

    }
    public function newAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function orderAction()
    {
        $this->loadLayout();
        $this->renderLayout();  
    }
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Product::ENTITY)->getTypeId();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function editAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function deleteAction()
    {
        
    }
    public function createattributeAction()
    {
      $this->loadLayout();
      $this->renderLayout();
    }
   
   
    public function saveattributeAction()
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
            // echo '<pre>';
            // print_r($model);
            // die;
            $model->save();
            $attributeId = $model->getId();
            if($data['attribute_group_id']){

                $groupModel = Mage::getModel('eav/entity_attribute');
                $groupModel->setEntityTypeId($this->_entityTypeId)
                ->setAttributeSetId($default_attribute_set_id)
                ->setAttributeGroupId($data['attribute_group_id'])
                ->setAttributeId($attributeId);
                $groupModel->save();
               
            }
            
            $session->addSuccess(
                Mage::helper('vendor')->__('The vendor attribute has been saved.'));

            Mage::app()->cleanCache(array(Mage_Core_Model_Translate::CACHE_TAG));
            $session->setAttributeData(false);
            $this->_redirect('*/*/showattribute', array());
            return;
        } catch (Exception $e) {
            $session->addError($e->getMessage());
            $session->setAttributeData($data);
            $this->_redirect('*/account/index', array('attribute_id' => $id, '_current' => true));
            return;
        }
    }
    $this->_redirect('*/*/showattribute');     
    }
    public function creategroupAction()
    {
      $this->loadLayout();
      $this->renderLayout();
    }
    public function savegroupAction()
    {
        $data = $this->getRequest()->getPost();
    
        $id = Mage::getModel('vendor/session')->getId();
        
        $attribute_group_id = $this->getRequest()->getParam('attribute_group_id');
        $group_id = $this->getRequest()->getParam('group_id');

        $default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
    						->getAttributeSetId('vendor_product', 'Default');

        $groupModel = Mage::getModel('vendor/product_attribute_group');
        $model = Mage::getModel('eav/entity_attribute_group');
       
        if($attribute_group_id && $group_id){
            $model->load($attribute_group_id);
            $groupModel->load($group_id);

            if(!$model->getId() || !$groupModel->getId()){
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('Invalid data')); 
                $this->_redirect('*/account/index');
                return;
            }

            $attribute_group_id = $this->getRequest()->getParam('attribute_group_id');
           
            $assignedAttributes = $this->getAssignedAttributeOfGroup();

            foreach ($assignedAttributes as $assignedAttribute) {
                $oldAssignedAttribute[] = $assignedAttribute->getData()['attribute_id'];
            }

            foreach ($data['assigned'] as $key => $value) {
                $newAssignedAttribute[] = $key;
            }

            $conn = Mage::getSingleton('core/resource')->getConnection('core_write');

            if (is_null($newAssignedAttribute)) {
                $result = $oldAssignedAttribute;
            } else {
                $result = array_diff($oldAssignedAttribute, $newAssignedAttribute);
            }
            $conn = Mage::getSingleton('core/resource')->getConnection('core_write');

            foreach ($result as $key => $attribute_id) {
                $sql = "DELETE FROM `eav_entity_attribute` WHERE `attribute_id` = $attribute_id";
                $conn->query($sql);
            }

          
            foreach($data['unassigned'] as $key=>$value){
                $eavEntityModel = Mage::getModel('eav/entity_attribute');
                $eavEntityModel->setEntityTypeId($this->_entityTypeId)
                ->setAttributeSetId($default_attribute_set_id)
                ->setAttributeGroupId($attribute_group_id)
                ->setAttributeId($key);
                $eavEntityModel->save();
            }
        }
        $groupname = $this->getRequest()->getPost('attribute_group_name');

        $model->setAttributeSetId($default_attribute_set_id)
            ->setAttributeGroupName($id.$groupname);
          
        $model->save();

        $groupId = $model->getAttributeGroupId();

        foreach($data['unassigned'] as $key=>$value){
            $eavEntityModel = Mage::getModel('eav/entity_attribute');
            $eavEntityModel->setEntityTypeId($this->_entityTypeId)
            ->setAttributeSetId($default_attribute_set_id)
            ->setAttributeGroupId($groupId)
            ->setAttributeId($key);
            $eavEntityModel->save();
        }

        $groupModel->setEntityId($id)
            ->setAttributeGroupId($groupId)
            ->setAttributeGroupName($groupname);
        
        $groupModel->save();
        $this->_redirect('*/*/showgroup');

        
    }

    public function showattributeAction()
    {
        $this->loadLayout();
        $this->renderLayout();
      
    }
    public function editattributeAction()
    {
        $this->loadLayout();
        $this->renderLayout();
      
    }
    public function showgroupAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function editgroupAction()
    {
       $this->loadLayout();
       $this->renderLayout();
    }
    public function deletegroupAction()
    {
      $attribute_group_id = $this->getRequest()->getParam('attribute_group_id');
      $group_id = $this->getRequest()->getParam('group_id');

        $model = Mage::getModel('eav/entity_attribute_group');
        $model->load($attribute_group_id);
        $model->delete();

        $groupModel = Mage::getModel('vendor/product_attribute_group');
        $groupModel->load($group_id);
        $groupModel->delete();

        $this->_redirect('*/*/showgroup');
    }

    public function deleteattributeAction()
    {
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            $model = Mage::getModel('vendor/resource_eav_productattribute');

            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->_entityTypeId) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This attribute cannot be deleted.'));
                $this->_redirect('*/*/');
                return;
            }

            try {
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('The product attribute has been deleted.'));
                $this->_redirect('*/*/showattribute');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('vendor')->__('Unable to find an attribute to delete.'));
        $this->_redirect('*/*/showattribute');
    }
}
?>