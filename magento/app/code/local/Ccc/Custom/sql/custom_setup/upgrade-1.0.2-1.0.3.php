<?php
$installer = $this;
$installer->startSetup();
$installer->addAttribute(Ccc_Custom_Model_Resource_Custom::ENTITY, 'phoneNo', array(
    'group'                      => 'General',
    'input'                      => 'text',
    'type'                       => 'varchar',
    'label'                      => 'phoneNo',
    'frontend_class'             => 'validate-digits',
    'backend'                    => '',
    'visible'                    => 1,
    'required'                   => 0,
    'user_defined'               => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 1,
    'visible_on_front'           => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front'   => 1,
    'global'                     => Ccc_Custom_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->endSetup();
?>