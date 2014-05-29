<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Headerslider_Block_Admin_Slide_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('headerslider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('headerslider')->__('Item Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('headerslider')->__('Item Information'),
            'title' => Mage::helper('headerslider')->__('Item Information'),
            'content' => $this->getLayout()->createBlock('headerslider/admin_slide_edit_tab_form')->toHtml(),
        ));
        $this->addTab('related', array(
            'label' => Mage::helper('catalog')->__('Related products'),
            'url' => $this->getUrl('*/*/related', array('_current' => true)),
            'class' => 'ajax',
        ));

        return parent::_beforeToHtml();
    }

}