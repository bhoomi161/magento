<?php

class Ccc_Custom_Block_Adminhtml_Custom_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'custom';
        $this->_controller = 'adminhtml_custom';
        parent::__construct();
        $this->_hideButton();
        $this->setId('custom_edit');
    }
    protected function _hideButton(){
        if(!$this->getRequest()->getParam('set') && !$this->getRequest()->getParam('id')){
            $this->_removeButton('save');
            $this->_removeButton('delete');
        }
    }
}

?>
<script type="text/javascript">
    var productTemplateSyntax = /(^|.|\r|\n)({{(\w+)}})/;
    function setSettings(urlTemplate, setElement) {
        var template = new Template(urlTemplate, productTemplateSyntax);
        setLocation(template.evaluate({attribute_set:$F(setElement)}));
    }
</script>
