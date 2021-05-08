<?php 

class Ccc_Process_Adminhtml_Process_ImportController extends Mage_Adminhtml_Controller_Action{
    public function indexAction()
    {
      $this->loadLayout();
      $this->_setActiveMenu('process');
      //$this->_addLeft($this->getLayout()->createBlock('process/adminhtml_process_import_edit_tabs'));
      $this->renderLayout();
    }
}