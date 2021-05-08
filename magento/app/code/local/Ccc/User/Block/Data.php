<?php 

class Ccc_User_Block_Data extends Mage_Core_Block_Template{
    

    public function getUserData()
    {
        return Mage::getModel('ccc_user/data')->getCollection();
    }

}