<?php
 
class Mage_HelloDeveloper_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo 'Hello developer... from magento';
    }
 
    public function sayHelloAction()
    {
        echo 'Hello one more time...';
    }
}
?>