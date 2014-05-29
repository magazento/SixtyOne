<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
?>
<?php

class Magazento_Tabs_Block_Admin_Tab_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('tabs_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tabs')->__('Tab Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section_tab', array(
            'label' => Mage::helper('tabs')->__('Tab Information'),
            'title' => Mage::helper('tabs')->__('Tab Information'),
            'content' => $this->getLayout()->createBlock('tabs/admin_tab_edit_tab_form')->toHtml(),
        ));
       $this->addTab('form_section_other', array(
           'label' => Mage::helper('tabs')->__('CSS & DESIGN'),
           'title' => Mage::helper('tabs')->__('CSS & DESIGN'),
           'content' => $this->getLayout()->createBlock('tabs/admin_tab_edit_tab_other')->toHtml(),
       ));

        return parent::_beforeToHtml();
    }

}