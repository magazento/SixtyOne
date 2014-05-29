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

class Magazento_Tabs_Block_Admin_Tab extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'admin_tab';
        $this->_blockGroup = 'tabs';
        $this->_headerText = Mage::helper('tabs')->__('Tabs Tabs grid');
        $this->_addButtonLabel = Mage::helper('tabs')->__('Add New Tab');
        parent::__construct();
    }

}
