<?php

class Ccc_Custom_CustomController extends Mage_Core_Controller_Front_Action{
    
    protected function _initCustom()
    {
        $categoryId = (int) $this->getRequest()->getParam('custom', false);
        $productId  = (int) $this->getRequest()->getParam('id');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);

        return Mage::helper('custom')->initCustom($customId, $this, $params);
    }

    public function galleryAction()
    {
        echo 'Hello';
        die;
        if (!$this->_initCustom()) {
            if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }
}

?>