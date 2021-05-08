<?php
class Ccc_User_AdminHtml_UserController extends Mage_Adminhtml_Controller_Action{
   
    public function _initAction(){
        $this->loadLayout()
        ->_setActiveMenu('user')
        ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage User'),Mage::helper('adminhtml')->__('Manage User'));
         return $this;
    }
    public function indexAction()
    {
        $this->_initAction();
        $this->_setActiveMenu('user');
        $this->_addContent($this->getLayout()->createBlock('ccc_user_admin/user'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $userId = $this->getRequest()->getParam('id');
        $userModel = Mage::getModel('ccc_user/data')->load($userId);
        if($userModel->getData() || $userId==0){
            Mage::register('user_data',$userModel);
            $this->loadLayout();
            $this->_setActiveMenu('user');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('ccc_user/adminhtml_user_edit'));
            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('user')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
    public function saveAction()
    {
        if($this->getRequest()->getPost()){
          try{
                $postData = $this->getRequest()->getPost();
                $id = $this->getRequest()->getParam('id');
                $userModel = Mage::getModel('ccc_user/data')->load($id);
                date_default_timezone_set('Asia/Kolkata');
                if($userModel->getId()){
                    $userModel->setFirstName($postData['first_name'])
                    ->setLastName($postData['last_name'])
                    ->setPassword(md5($postData['password']))
                    ->setStatus($postData['status'])
                    ->setEmail($postData['email'])
                    ->setMobile($postData['mobile'])
                    ->setUpdateDate(date('Y-m-d H:i:s'))
                    ->save();
                }
                else{
                    $userModel->setId($this->getRequest()->getParam('id'))
                    ->setFirstName($postData['first_name'])
                    ->setLastName($postData['last_name'])
                    ->setPassword(md5($postData['password']))
                    ->setStatus($postData['status'])
                    ->setEmail($postData['email'])
                    ->setMobile($postData['mobile'])
                    ->setCreatedDate(date('d-m-Y H:i:s'))
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setUserData(false);
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
                $userModel = Mage::getModel('ccc_user/data');
                $userModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')
                ->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit',array('id'=>$this->getRequest()->getParam('id')));

            }
        }
        $this->_redirect('*/*/');

    }
}

?>