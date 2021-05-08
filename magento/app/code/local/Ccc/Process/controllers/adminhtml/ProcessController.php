<?php

class Ccc_Process_Adminhtml_ProcessController extends Mage_Adminhtml_Controller_Action{
    public function indexAction()
    {
       $this->loadLayout();
       $this->_setActiveMenu('process');
       $this->_title('process');
       $this->_addContent($this->getLayout()->createBlock('process/adminhtml_content'));
       $this->renderLayout();
    }
    public function renderAction()
    {
        $content = $this->getLayout()->createBlock('process/adminhtml_process')->toHtml();
        $response = [
            'status'=>'success',
            'message'=>'process grid',
            'element'=>[
                [   'selector'=>'#contentHtml',
                    'html'=>$content
                ]
            ]
            ];
        
        header('Content-type: application/json; charset=utf-8');
	    $this->getResponse()->setBody(json_encode($response));
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $content = $this->getLayout()->createBlock('process/adminhtml_process_edit')->toHtml();
        $response = [
            'status'=>'success',
            'message'=>'process grid',
            'element'=>[
                [   'selector'=>'#contentHtml',
                    'html'=>$content
                ]
            ]
            ];
        
        header('Content-type: application/json; charset=utf-8');
	    $this->getResponse()->setBody(json_encode($response));



        
        // $processId = $this->getRequest()->getParam('id');
        // $processModel = Mage::getModel('process/process')->load($processId);
        // if($processModel->getData() || $processId==0){
        //     Mage::register('process_data',$processModel);
        //     $this->loadLayout();
        //     $this->_setActiveMenu('process/process');

        //     $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        //     $this->_addContent($this->getLayout()->createBlock('process/adminhtml_process_edit'));
        //     $this->renderLayout();

        // }
        // else{
        //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('process')->__('process does not exist'));
        //     $this->_redirect('*/*/');
        // }
    }
    public function saveAction()
    {
        if($this->getRequest()->getPost()){
          try{
                $postData = $this->getRequest()->getPost();
                $id = $this->getRequest()->getParam('id');
                $processModel = Mage::getModel('process/process')->load($id);
                date_default_timezone_set('Asia/Kolkata');

                if($processModel->getId()){
                    $processModel->addData($postData)
                    ->save();
                }
                else{
                    $processModel->setId($this->getRequest()->getParam('id'))
                    ->addData($postData)
                    ->setCreatedDate(date('d-m-Y H:i:s'))
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('process was successfully saved'));
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
                $processModel = Mage::getModel('process/process');
                $processModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')
                ->__('process was successfully deleted'));
                $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));

            }
        }
        $this->_redirect('*/*/');

    }
}