<?php

class Ccc_Vendor_ProductController extends Mage_Core_Controller_Front_Action{

    protected $_resources;
    protected $_entityTypeId;
    
    public function newAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
        }
        $this->_forward('edit');
    }

    public function gridAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
        }
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
    }
    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
    public function editAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
        }
        $productId = (int) $this->getRequest()->getParam('product_id');
        $product = $this->_initProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    protected function _initProduct()
    {
        $this->_title($this->__('Vendor'))
            ->_title($this->__('Manage Products'));

        $productId = (int) $this->getRequest()->getParam('id');
        $product = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }
        } else {
            $product->load($productId);
        }
        Mage::register('current_product', $product);
        return $product;
    }
    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                $productId = $this->getRequest()->getParam('product_id');
                $vendorId = $this->_getSession()->getVendor()->getId();
                
                $vendorProductModel = Mage::getModel('vendor/product')
                ->load($productId);

                $sku = $postData['sku'];

                $isSku = Mage::getModel('vendor/product')->getResource()->getSkuById($sku);

                if(!$productId){
                    if ($isSku) {
                        throw new Exception("Product SKU already exists! (SKU must be unique.)");
                    }
                    $existsCatalogProduct = Mage::getModel('catalog/product')->getResource()->getIdBySku($sku);
                    if ($existsCatalogProduct) {
                        throw new Exception("Product SKU already exists!");
                    }
                }
               
                $requestModel = Mage::getModel('vendor/product_request');

                if($vendorProductModel->getId()){
                     $vendorProductModel->addData($postData);    
                }
                else 
                {
                    $vendorProductModel->addData($postData);
                    $vendorProductModel->setVendorId($vendorId);
                }
                
                date_default_timezone_set("Asia/kolkata");

                $vendorProductModel->save();
                $entityId = $vendorProductModel->getId();

                if($productId){
                    $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
                    ->addFilter('product_id',$productId);
                    $request_id = $requestModelCollection->getData()[0]['request_id'];

                    $requestModel->setRequestId($request_id)
                        ->setVendorId($vendorId)
                        ->setProductId($productId)
                        ->setRequestType('edit')
                        ->setApproveStatus('pending')
                        ->setCreatedAt(date('Y-m-d H:i:s'));
                }
                else{
                    $requestModel->setVendorId($vendorId)
                    ->setProductId($entityId)
                    ->setRequestType('insert')
                    ->setApproveStatus('pending')
                    ->setCreatedAt(date('Y-m-d H:i:s'));
                }
                $requestModel->save();
                $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
                $sql = "update `vendor_product` set vendor_id = $vendorId,sku = '$sku' where entity_id = $entityId";
                $conn->query($sql);

                $this->_getSession()->addSuccess(
                    $this->__('The Product has been saved.')
                );
                $this->_redirect('*/*/grid');
                return;
            } catch (Exception $e) {
                $this->_redirect('vendor/product/new');
                Mage::getSingleton('vendor/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/grid');
    }
    public function deleteAction()
    {
       try {

            $vendorModel = Mage::getModel('vendor/product');
            if (!($productId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$vendorModel->load($productId)) {
                throw new Exception('vendor does not exist');
            }
            date_default_timezone_set("Asia/kolkata");

            $vendorId = $this->_getSession()->getVendor()->getId();
            $requestModel = Mage::getModel('vendor/product_request');
            $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
            ->addFilter('product_id',$productId);
            $request_id = $requestModelCollection->getData()[0]['request_id'];
            $status = $requestModelCollection->getData()[0]['approve_status'];

            $requestModel->setRequestId($request_id)
                ->setVendorId($vendorId)
                ->setProductId($productId)
                ->setRequestType('delete')
                ->setApproveStatus('pending')
                ->setCreatedAt(date('Y-m-d H:i:s'));
            $requestModel->save();

            if($status == 'pending'){
                if (!$vendorModel->delete()) {
                    throw new Exception('Error in delete record', 1);
                }
            }
            
            Mage::getSingleton('core/session')->addSuccess($this->__('Your Request for deleting product has been submitted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/grid');
    }
    
}