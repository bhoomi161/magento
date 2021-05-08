<?php

class Ccc_Process_Adminhtml_Process_Type_ColumnController extends Mage_Adminhtml_Controller_Action{
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
        $content = $this->getLayout()->createBlock('process/adminhtml_process_type_column')->toHtml();
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
        $content = $this->getLayout()->createBlock('process/adminhtml_process_type_column_edit')->toHtml();
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
        // $processTypeId = $this->getRequest()->getParam('id');
        // $processTypeModel = Mage::getModel('process/process_type_column')->load( $processTypeId);
        // if($processTypeModel->getData() || $processTypeId==0){
        //     Mage::register('process_group_data',$processTypeModel);
        //     $this->loadLayout();
        //     $this->_setActiveMenu('process/process');

        //     $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        //     $this->_addContent($this->getLayout()->createBlock('process/adminhtml_process_type_column_edit'));
        //     $this->renderLayout();

        // }
        // else{
        //     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('process')->__('process type column does not exist'));
        //     $this->_redirect('*/*/');
        // }
    }
    public function saveAction()
    {
        if($this->getRequest()->getPost()){
          try{
                $postData = $this->getRequest()->getPost();
                $id = $this->getRequest()->getParam('id');
                $processTypeModel = Mage::getModel('process/process_type_column')->load($id);

                if($processTypeModel->getId()){
                   $processTypeModel->addData($postData)
                    ->save();
                }
                else{
                   $processTypeModel->setId($this->getRequest()->getParam('id'))
                    ->addData($postData)
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('process group was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setProcessTypeColumnData(false);
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
               $processTypeModel = Mage::getModel('process/process_type_column');
               $processTypeModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')
                ->__('process type column was successfully deleted'));
                $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));

            }
        }
        $this->_redirect('*/*/');

    }
}