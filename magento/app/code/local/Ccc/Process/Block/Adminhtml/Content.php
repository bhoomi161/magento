<?php

class Ccc_Process_Block_Adminhtml_Content extends Mage_Adminhtml_Block_Widget_Grid{
    protected $tabs = [];
    public function __construct()
    {
        $this->setTemplate('process/tabContent.phtml');
        $this->prepareTabs();
    }
    public function addTab($key,array $data)
    {
        $this->tabs[$key] = $data;
        return $this;
    }
    public function prepareTabs()
    {
      $this->addTab('process',
      ['label'=>'Process',
        'url'=>$this->getUrl('*/adminhtml_process/render')]
    )
    ->addTab('process_type',
      ['label'=>'Process Type',
        'url'=>$this->getUrl('*/adminhtml_process_type/render')]
    )
    ->addTab('process_type_column',
      ['label'=>'Process Type Column',
        'url'=>$this->getUrl('*/adminhtml_process_type_column/render')]
    )
    ->addTab('process_group',
      ['label'=>'Process Group',
        'url'=>$this->getUrl('*/adminhtml_process_group/render')]
    )
    ->addTab('process_log',
      ['label'=>'Process Log',
        'url'=>$this->getUrl('*/adminhtml_process_log/render')]
    )
    ->addTab('process_log_index',
      ['label'=>'Process Log Index',
        'url'=>$this->getUrl('*/adminhtml_process_log_idx/render')]
    );

    }
    public function getDefaultTabcontent()
    {
       return $this->getLayout()->createBlock('process/adminhtml_process')->toHtml();
    }
    public function getTabs()
    {
       return $this->tabs;
    }
}