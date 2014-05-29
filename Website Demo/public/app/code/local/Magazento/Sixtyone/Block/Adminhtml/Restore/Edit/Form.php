<?php
/**
 *
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Block_Adminhtml_Restore_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $isElementDisabled = false;
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Restore Parameters')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => 0
            ));
        }

        $fieldset->addField('setup_package', 'checkbox', array(
            'label' => Mage::helper('sixtyone')->__('Deactivate Theme'),
            'required' => false,
            'name' => 'setup_package',
            'value' => 1,
            'after_element_html' => 'Check it if you want to deactivate the theme',
        ));

        $fieldset->addField('clear_scope', 'checkbox', array(
                'name'  => 'clear_scope',
                'value' => 1,
                'label' => Mage::helper('sixtyone')->__('Clear Configuration'),
                'title' => Mage::helper('sixtyone')->__('Clear Configuration'),
                'after_element_html' => 'Check to restore the theme default settings',
            )
        );

        $fieldset->addField('setup_pages', 'checkbox', array(
            'label' => Mage::helper('sixtyone')->__('Restore Cms Pages'),
            'required' => false,
            'name' => 'setup_pages',
            'value' => 1,
            'after_element_html' => 'Check to restore all demo pages',
            'note' => Mage::helper('sixtyone')->__('All changes you made in these pages will be lost'),

        ));

        $fieldset->addField('setup_blocks', 'checkbox', array(
            'label' => Mage::helper('sixtyone')->__('Restore Cms Blocks'),
            'required' => false,
            'name' => 'setup_blocks',
            'value' => 1,
            'after_element_html' => 'Check to restore all demo blocks',
            'note' => Mage::helper('sixtyone')->__('All changes you made in these pages will be lost'),
        ));


        $form->setAction($this->getUrl('*/*/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
