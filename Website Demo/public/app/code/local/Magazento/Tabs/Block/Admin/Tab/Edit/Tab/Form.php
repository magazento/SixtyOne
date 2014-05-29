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

class Magazento_Tabs_Block_Admin_Tab_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('tabs_tab');
        $form = new Varien_Data_Form(array('id' => 'edit_form_tab', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('tab_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('tabs')->__('General Information'), 'class' => 'fieldset-wide'));
        if ($model->getTabId()) {
            $fieldset->addField('tab_id', 'hidden', array(
                'name' => 'tab_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => Mage::helper('tabs')->__('Title'),
            'title' => Mage::helper('tabs')->__('Title'),
            'required' => true,
        ));
        $fieldset->addField('url', 'text', array(
            'name' => 'url',
            'label' => Mage::helper('tabs')->__('Url'),
            'title' => Mage::helper('tabs')->__('Url'),
            'required' => false,
        ));
		
        $fieldset->addField('type', 'select', array(
            'name' => 'type',
            'label' => Mage::helper('tabs')->__('Type'),
            'title' => Mage::helper('tabs')->__('Type'),
            'required' => true,
            'options' => array(
                1 => Mage::helper('tabs')->__('Tab'),
                0 => Mage::helper('tabs')->__('Link'),
            ),
        ));

        $fieldset->addField('position', 'select', array(
            'name' => 'position',
            'label' => Mage::helper('tabs')->__('Position'),
            'title' => Mage::helper('tabs')->__('Position'),
            'required' => true,
            'options' => Mage::helper('tabs')->numberArray(20,Mage::helper('tabs')->__('')),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('tabs')->__('Store View'),
                'title' => Mage::helper('tabs')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            'style' => 'height:150px',
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField('align_tab', 'select', array(
            'label' => Mage::helper('tabs')->__('Align tab'),
            'title' => Mage::helper('tabs')->__('Align tab'),
            'name' => 'align_tab',
            'required' => true,
            'options' => array(
                'left' => Mage::helper('tabs')->__('Left'),
                'right' => Mage::helper('tabs')->__('Right'),
            ),
        ));
		$fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('tabs')->__('Status'),
            'title' => Mage::helper('tabs')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('tabs')->__('Enabled'),
                '0' => Mage::helper('tabs')->__('Disabled'),
            ),
        ));
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $fieldset->addField('from_time', 'date', array(
            'name' => 'from_time',
            'time' => true,
            'label' => Mage::helper('tabs')->__('From Time'),
            'title' => Mage::helper('tabs')->__('From Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

        $fieldset->addField('to_time', 'date', array(
            'name' => 'to_time',
            'time' => true,
            'label' => Mage::helper('tabs')->__('To Time'),
            'title' => Mage::helper('tabs')->__('To Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));


        if (Mage::helper('tabs')->versionUseWysiwig()) {
            $wysiwygConfig = Mage::getSingleton('tabs/wysiwyg_config')->getConfig();
        } else {
            $wysiwygConfig = '';
        }
		
        $fieldset->addField('content', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('tabs')->__('Content'),
            'title' => Mage::helper('tabs')->__('Content'),
            'style' => 'height:36em',
            'config' => $wysiwygConfig,
            'required' => false,
        ));
		

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
