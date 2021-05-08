<?php

class Ccc_Process_Adminhtml_Process_LogController extends Mage_Adminhtml_Controller_Action{
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
        $content = $this->getLayout()->createBlock('process/adminhtml_process_log')->toHtml();
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
}