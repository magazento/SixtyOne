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

class Magazento_Tabs_Block_Admin_Tab_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
    	$this->_objectId = 'tab_id';
        $this->_controller = 'admin_tab';
        $this->_blockGroup = 'tabs';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('tabs')->__('Save tab'));
        $this->_updateButton('delete', 'label', Mage::helper('tabs')->__('Delete tab'));
        
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
        if (Mage::registry('tabs_tab')->getId()) {
            return Mage::helper('tabs')->__("Edit tab '%s'", $this->htmlEscape(Mage::registry('tabs_tab')->getTitle()));
        }
        else {
            return Mage::helper('tabs')->__('New tab');
        }
    }

}
