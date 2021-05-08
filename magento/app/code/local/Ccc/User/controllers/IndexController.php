<?php
class Ccc_User_IndexController extends Mage_Core_Controller_Front_Action{
    public function testAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
    public function formAction()
    {   
        $this->loadLayout();
        $this->renderLayout();
    }
    public function SaveAction()
    {
        $userData = $this->getRequest()->getPost('user');
        date_default_timezone_set('Asia/Kolkata');
        $user = Mage::getModel('ccc_user/data');
        $user->setData($userData);
        $user->created_date = date('d-m-Y H:i:s');
       
        try{
            $user->save();
            $this->_redirect('*/*/test');
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function deleteAction()
    {
        $user = Mage::getModel('ccc_user/data');
        $user->setId(2);
        try{
            $user->delete();
            echo 'Delete success';
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function gridAction()
    {
       $data = Mage::getModel('ccc_user/data')->getCollection();
       foreach($data as $user){
           echo '<br>Data Id:'.$user->getId();
           echo '<br>Name:'.$user->getFirstName();
       }
    }
}

?>