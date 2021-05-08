<?php

class Ccc_Vendor_Adminhtml_VendorproductController extends Mage_Adminhtml_Controller_Action
{

    protected $_entityTypeId;

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('vendor/product');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('vendor');
        $this->_title('Vendor Product Grid');

        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendorproduct'));

        $this->renderLayout();
    }

    protected function _initVendor()
    {
        $this->_title($this->__('Vendor Product'))
            ->_title($this->__('Manage vendor products'));

        $vendorId = (int) $this->getRequest()->getParam('id');
        $vendor   = Mage::getModel('vendor/product') 
        ->setStoreId($this->getRequest()->getParam('store', 0))
        ->load($vendorId);

           
        Mage::register('current_vendorproduct', $vendor);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $vendor;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $vendorId = (int) $this->getRequest()->getParam('id');
        $vendor   = $this->_initVendor();

        if ($vendorId && !$vendor->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This vendor no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($vendor->getName());

        $this->loadLayout();

        $this->_setActiveMenu('vendor/vendor');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();

    }

    public function saveAction()
    {
        try {

            $vendorData = $this->getRequest()->getPost('account');

            $vendor = Mage::getSingleton('vendor/product');
            
            if ($vendorId = $this->getRequest()->getParam('id')){

                if (!$vendor->load($vendorId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            }

            $vendor->addData($vendorData);
            $vendor->save();

            Mage::getSingleton('core/session')->addSuccess("Vendor data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {

            $vendorModel = Mage::getModel('vendor/product');

            if (!($vendorId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$vendorModel->load($vendorId)) {
                throw new Exception('vendor does not exist');
            }

            if (!$vendorModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The vendor has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Product::ENTITY)->getTypeId();
    }
    public function approveAction()
    {
        $vendorProductModel = Mage::getModel('vendor/product');
        $productId = $this->getRequest()->getParam('id');

        $default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
        ->getAttributeSetId('vendor_product', 'Default');

        $vendorProductData = $vendorProductModel->load($productId)->getData();
        $catalogProductModel = Mage::getModel('catalog/product');
        $requestModel = Mage::getModel('vendor/product_request');

        $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
            ->addFilter('product_id',$productId);
        $request_type = $requestModelCollection->getData()[0]['request_type'];
        $request_id = $requestModelCollection->getData()[0]['request_id'];
        $catalogProductId = $requestModelCollection->getData()[0]['catalog_product_id'];

        try{

            if($request_type == 'insert'){
                $catalogProductModel->addData($vendorProductData)
                ->setAttributeSetId($default_attribute_set_id)
                ->setTypeId('simple')
                ->setEntityTypeId($this->_entityTypeId)
                ->save();
    
                $catalogProductId = $catalogProductModel->getId();                
        
                $requestModel->setRequestId($request_id)
                ->setCatalogProductId($catalogProductId)
                ->setApproveStatus('approved')
                ->setApprovedAt(date('Y-m-d H:i:s'));
                $requestModel->save();
    
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('product has been approved'));
                $this->_redirect('*/*/index');
                return;
            }
            if($request_type == 'edit'){
                
                $catalogProductModel->load($catalogProductId)
                    ->addData($vendorProductData)
                    ->setAttributeSetId($default_attribute_set_id)
                     ->save();

                $requestModel->load($request_id)
                    ->setApproveStatus('approved')
                    ->setApprovedAt(date(('Y-m-d H:i:s')))
                    ->save();
            }
            if($request_type=='delete'){
               
                if($vendorProductModel->load($productId)){
                    $vendorProductModel->delete();
                }
                // if($catalogProductModel->load($catalogProductId)){
                //     $catalogProductModel->delete();
                // }
                if($requestModel->load($request_id)){

                    $requestModel->setApproveStatus('approved')
                        ->setApprovedAt(date(('Y-m-d H:i:s')))
                        ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('product has been deleted'));
                $this->_redirect('*/*/index');
                return;
            }
           
        }
        catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
        $this->_redirect('*/*/index');
        
    }
    public function rejectAction()
    {
        try{
            $productId = $this->getRequest()->getParam('id');
            $requestModel = Mage::getModel('vendor/product_request');
            $vendorProductModel = Mage::getModel('vendor/product');
            $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
            ->addFilter('product_id',$productId);
            $request_id = $requestModelCollection->getData()[0]['request_id'];
            $request_type = $requestModelCollection->getData()[0]['request_type'];

            $requestModel->setRequestId($request_id)
            ->setApproveStatus('rejected')
            ->setApprovedAt(date('Y-m-d H:i:s'));
            $requestModel->save();

            if($request_type=='edit'){
               $session = Mage::getSingleton('core/session');
               $data = $session->getData($productId);
                echo '<pre>';
                print_r($data);
                die;
                $vendorProductModel->addData($data);
                $vendorProductModel->save();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess('Request Rejected');
            return;
        }
        catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
        $this->_redirect('*/*/index');
    }
}
