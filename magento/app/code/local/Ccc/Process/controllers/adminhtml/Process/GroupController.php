<?php

class Ccc_Process_Adminhtml_Process_GroupController extends Mage_Adminhtml_Controller_Action{
    public function indexAction()
    {
       $this->loadLayout();
       $this->_setActiveMenu('process');
       $this->_title('process');
       $this->_addContent($this->getLayout()->createBlock('process/adminhtml_content'));
       $this->renderLayout();
    }
    public function _initProcessGroup()
    {
        $groupId = $this->getRequest()->getParam('id');
        $groupModel = Mage::getModel('process/process')->load($groupId);
        Mage::register('process_group_data',$groupModel);
        
    }
    public function renderAction()
    {
        $content = $this->getLayout()->createBlock('process/adminhtml_process_group')->toHtml();
        $response = [
            'status'=>'success',
            'message'=>'process grid',
            'element'=>[
                [   'selector'=>'#contentHtml',
                    'html'=>$content
                ]
            ],
        ];
        header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $content = $this->getLayout()->createBlock('process/adminhtml_process_group_edit')->toHtml();
        $response = [
            'status'=>'success',
            'message'=>'process grid',
            'element'=>[
                [   'selector'=>'#contentHtml',
                    'html'=>$content
                ]
            ],
        ];
        header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);
        $this->_initProcessGroup();
        // $groupId = $this->getRequest()->getParam('id');
        // $groupModel = Mage::getModel('process/process')->load($groupId);
        // if($groupModel->getData() || $groupId==0){
        //     Mage::register('process_group_data',$groupModel);
        //     $this->loadLayout();
        //     $this->_setActiveMenu('process/process');

        //     $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        //     $this->_addContent($this->getLayout()->createBlock('process/adminhtml_process_group_edit'));
        //     $this->renderLayout();

        // }
        // else{
        //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('process')->__('process group does not exist'));
        //     $this->_redirect('*/*/');
        // }
    }
    public function saveAction()
    {
        
        if($this->getRequest()->getParams()){
          try{
                $postData = $this->getRequest()->getParams();
                $id = $this->getRequest()->getParam('id');
                $groupModel = Mage::getModel('process/process_group')->load($id);
                date_default_timezone_set('Asia/Kolkata');

                if($groupModel->getId()){
                   $groupModel->addData($postData)
                    ->save();
                }
                else{
                   $groupModel->setId($this->getRequest()->getParam('id'))
                    ->addData($postData)
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('process group was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setprocessData(false);
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
               $groupModel = Mage::getModel('process/process_group');
               $groupModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')
                ->__('process group was successfully deleted'));
                $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));

            }
        }
        $this->_redirect('*/*/');

    }
}