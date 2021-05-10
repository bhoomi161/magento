<?php

class Ccc_Salesman_adminhtml_SalesmanController extends Mage_Adminhtml_Controller_Action{
    public function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('salesman')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Salesman'),Mage::helper('adminhtml')->__('Manage Salesman'));
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->_setActiveMenu('salesman');
        $this->_addContent($this->getLayout()->createBlock('salesman/Adminhtml_salesman'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $salesmanId = $this->getRequest()->getParam('id');
        $salesmanModel = Mage::getModel('ccc_salesman/data')->load($salesmanId);
        if($salesmanModel->getData() || $salesmanId==0){
            Mage::register('salesman_data',$salesmanModel);
            $this->loadLayout();
            $this->_setActiveMenu('Salesman/salesman');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('salesman/adminhtml_salesman_edit'));
            $this->renderLayout();

        }
        else{
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('salesman')->__('Salesman does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if($this->getRequest()->getPost()){
          try{
                $postData = $this->getRequest()->getPost();
                $id = $this->getRequest()->getParam('id');
                $salesmanModel = Mage::getModel('ccc_salesman/data')->load($id);
                date_default_timezone_set('Asia/Kolkata');

                if($salesmanModel->getId()){
                    $salesmanModel->setName($postData['name'])
                    ->setEmail($postData['email'])
                    ->setMobile($postData['mobile'])
                    ->save();
                }
                else{
                    $salesmanModel->setId($this->getRequest()->getParam('id'))
                    ->setName($postData['name'])
                    ->setEmail($postData['email'])
                    ->setMobile($postData['mobile'])
                    ->setCreatedDate(date('d-m-Y H:i:s'))
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Salesman was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setSalesmanData(false);
                $this->_redirect('*/*/');

          } catch(Exception $e){
              Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
              Mage::getSingleton('adminhtml/session')->setUserData($this->getRequest()->getPost());
              $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));
              return ;
          }
        }
        $this->_redirect('*/*/');
    }
    public function deleteAction()
    {
        if($this->getRequest()->getParam('id') > 0){
            try{
                $salesmanModel = Mage::getModel('ccc_salesman/data');
                $salesmanModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')
                ->__('Salesman was successfully deleted'));
                $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));

            }
        }
        $this->_redirect('*/*/');

    }
}