<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Block_Admin_Slide extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'admin_slide';
        $this->_blockGroup = 'headerslider';
        $this->_headerText = Mage::helper('headerslider')->__('Item');
        $this->_addButtonLabel = Mage::helper('headerslider')->__('Add New Item');
        parent::__construct();
    }

}
