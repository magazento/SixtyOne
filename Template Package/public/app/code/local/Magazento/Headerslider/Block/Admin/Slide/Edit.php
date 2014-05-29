<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Block_Admin_Slide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
    	$this->_objectId = 'slide_id';
        $this->_controller = 'admin_slide';
        $this->_blockGroup = 'headerslider';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('headerslider')->__('Save Slide'));
        $this->_updateButton('delete', 'label', Mage::helper('headerslider')->__('Delete Slide'));
        
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        

        $this->_formScripts[] = "
           function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }
            
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('headerslider_slide')->getId()) {
            return Mage::helper('headerslider')->__("Edit slide #%s", $this->htmlEscape(Mage::registry('headerslider_slide')->getId()));
        }
        else {
            return Mage::helper('headerslider')->__('New Item');
        }
    }

}
