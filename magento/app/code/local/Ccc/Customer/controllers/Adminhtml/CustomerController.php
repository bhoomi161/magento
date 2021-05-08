<?php

require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'CustomerController.php';

class Ccc_Customer_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
    public function indexAction()
    {
        echo 'Hello';
    }
}