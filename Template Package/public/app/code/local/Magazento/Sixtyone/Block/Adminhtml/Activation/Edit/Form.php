<?php
/**
 *
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Block_Adminhtml_Activation_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Activate Parameters')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => false,
                'value' => 0,
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => 0
            ));
        }

        $fieldset->addField('setup_pages', 'checkbox', array(
            'label' => Mage::helper('sixtyone')->__('Create Cms Pages'),
            'required' => false,
            'name' => 'setup_pages',
            'value' => 1,
        ))->setIsChecked(1);

        $fieldset->addField('setup_blocks', 'checkbox', array(
            'label' => Mage::helper('sixtyone')->__('Create Cms Blocks'),
            'required' => false,
            'name' => 'setup_blocks',
            'value' => 1,
        ))->setIsChecked(1);

        $fieldset->addField('setup_note', 'label', array(
            'label' => Mage::helper('sixtyone')->__('Important Note'),
            'value' => Mage::helper('sixtyone/template')->getInstallationNote()
        ));

        $form->setAction($this->getUrl('*/*/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
