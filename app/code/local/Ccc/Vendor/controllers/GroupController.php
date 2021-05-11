<?php

class Ccc_Vendor_GroupController extends Mage_Core_Controller_Front_Action{

    public function _initAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
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
    public function preDispatch()
    {
        //$this->_setForcedFormKeyActions('delete');
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Product::ENTITY)->getTypeId();
    }

    protected function _getSession()
    {
       return Mage::getSingleton('vendor/session');
    }

    public function indexAction()
    {
       if(!$this->_getSession()->isLoggedIn()){
           $this->_redirect('vendor/account/login');
       }
       $this->_initAction();
    }

    public function editAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
        $this->_initAction();
    }

    public function newAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
        $this->loadLayout();
        $this->renderLayout();
    }
    public function gridAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
       $this->_initAction();
    }

    public function saveAction()
    {
        if(!$this->_getSession()->isLoggedIn()){
            $this->_redirect('vendor/account/login');
        }
        $data = $this->getRequest()->getPost();
        // echo '<pre>';
        // print_r($data);
        // die;

        $id = $this->_getSession()->getVendor()->getId();
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
                $this->_redirect('*/*/edit');
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
            } 
            else 
            {
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
        try{
            $model->save();

            $groupId = $model->getAttributeGroupId();
       
            foreach($data['unassigned'] as $key=>$value){
                $eavEntityModel = Mage::getModel('eav/entity_attribute');
                $eavEntityModel->setEntityTypeId($this->_entityTypeId)
                ->setAttributeSetId($default_attribute_set_id)
                ->setAttributeGroupId($groupId)
                ->setAttributeId($key)
                ->save();
            }

             $groupModel->setEntityId($id)
            ->setAttributeGroupId($groupId)
            ->setAttributeGroupName($groupname);
        
            $groupModel->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('vendor')->__('The group has been saved'));
            $this->_redirect('*/*/grid');
            return;
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('attribute_group_id' => $this->getRequest()->getParam('attribute_group_id')));
            return;
        }
        

    }
    
    public function deleteAction()
    {
       
        $attribute_group_id = $this->getRequest()->getParam('attribute_group_id');
        $group_id = $this->getRequest()->getParam('group_id');
        
        $model = Mage::getModel('eav/entity_attribute_group');
        $groupModel = Mage::getModel('vendor/product_attribute_group');

        $model->load($attribute_group_id);
        $groupModel->load($group_id);

        if($attribute_group_id && $group_id){
            if (!$model->getId() || !$groupModel->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('vendor')->__('This group cannot be deleted.'));
                $this->_redirect('*/*/grid');
                return;
            }
    
            try{
                $model->delete();
                $groupModel->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('The group has been deleted.'));
                $this->_redirect('*/*/grid');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('attribute_group_id' => $this->getRequest()->getParam('attribute_group_id'),'group_id'=>$this->getRequest()->getParam('group_id')));
                return;
            }
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('vendor')->__('Unable to find group to delete.'));
            $this->_redirect('*/*/grid');
            }
    }


}