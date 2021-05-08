<?php

class Ccc_Custom_Adminhtml_CustomController extends Mage_Adminhtml_Controller_Action{
   
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('custom/custom');
    }

    protected function _initCustom()
    {
        $this->_title($this->__('Custom'))
            ->_title($this->__('Manage customs'));

        $customId = (int) $this->getRequest()->getParam('id');
        $custom   = Mage::getModel('custom/custom')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($customId);

        Mage::register('custom',$custom);
        Mage::register('current_custom', $custom);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $custom;
    }
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('custom');
        $this->_title('Custom Grid');

        $this->_addContent($this->getLayout()->createBlock('custom/Adminhtml_custom'));
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $customId = (int) $this->getRequest()->getParam('id');
        $custom   = $this->_initCustom();

        if ($customId && !$custom->getId()) {
            $this->_getSession()->addError(Mage::helper('custom')->__('This custom no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($custom->getName());

        $this->loadLayout();

        $this->_setActiveMenu('custom');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();

    }

    public function saveAction()
    {
        try {
            
            $customData = $this->getRequest()->getPost();
            
            $custom = $this->_initCustom();

            if ($customId = $this->getRequest()->getParam('id')) {
                if (!$custom->load($customId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            }
            $setId = $this->getRequest()->getParam('setId');
            $custom->setAttributeSetId($setId);
            $custom->addData($customData); 
           
            if($custom->save()){
                Mage::getSingleton('core/session')->addSuccess("custom data added.");
                $this->_redirect('*/*/');
            }

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {

            $customModel = Mage::getModel('custom/custom');

            if (!($customId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$customModel->load($customId)) {
                throw new Exception('custom does not exist');
            }

            if (!$customModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The vendor has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            $Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }

}