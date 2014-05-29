<?php
/**
 *
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Block_Admin_Slide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareForm() {
        $model = Mage::registry('headerslider_slide');
        $form = new Varien_Data_Form(array('id' => 'edit_form_slide', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('slide_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('headerslider')->__('General Information'), 'class' => 'fieldset-wide'));
        if ($model->getSlideId()) {
            $fieldset->addField('slide_id', 'hidden', array(
                'name' => 'slide_id',
            ));
        }

        $fieldset->addField('position', 'text', array(
            'name' => 'position',
            'label' => Mage::helper('headerslider')->__('Position'),
            'title' => Mage::helper('headerslider')->__('Position'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('headerslider')->__('Status'),
            'title' => Mage::helper('headerslider')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('headerslider')->__('Enabled'),
                '0' => Mage::helper('headerslider')->__('Disabled'),
            ),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('headerslider')->__('Store View'),
                'title' => Mage::helper('headerslider')->__('Store View'),
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

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $fieldset->addField('from_time', 'date', array(
            'name' => 'from_time',
            'time' => true,
            'label' => Mage::helper('headerslider')->__('From Time'),
            'title' => Mage::helper('headerslider')->__('From Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

        $fieldset->addField('to_time', 'date', array(
            'name' => 'to_time',
            'time' => true,
            'label' => Mage::helper('headerslider')->__('To Time'),
            'title' => Mage::helper('headerslider')->__('To Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));


        if (Mage::helper('headerslider')->versionUseWysiwig()) {
            $wysiwygConfig = Mage::getSingleton('headerslider/wysiwyg_config')->getConfig();
        } else {
            $wysiwygConfig = '';
        }

        $image = '';
        $path = Mage::helper('headerslider')->getImageFilePath() . DS . $model->getData('image_filename'); 
        if (is_file($path)) {
           $image = '<img width="250" height="200" src="'.Mage::helper('headerslider')->getImageFileHttp().'/'.$model->getData('image_filename').'">';
        }   
        
        $fieldset->addField('image', 'image', array(
          'label'     => Mage::helper('headerslider')->__('Image'),
          'required'  => true,
          'name'      => 'image',
          'note'      => $image,
        ));  
        
        $fieldset->addField('content', 'editor', array(
            'name' => 'content',
            'label' => Mage::helper('headerslider')->__('Content'),
            'title' => Mage::helper('headerslider')->__('Content'),
            'style' => 'height:36em',
            'config' => $wysiwygConfig,
            'required' => true,
        ));

        $fieldset->addField('script_java', 'note', array(
            'text' => '<script type="text/javascript">
				            var inputDateFrom = document.getElementById(\'slide_from_time\');
				            var inputDateTo = document.getElementById(\'slide_to_time\');
            				inputDateTo.onchange=function(){dateTestAnterior(this)};
				            inputDateFrom.onchange=function(){dateTestAnterior(this)};


				            function dateTestAnterior(inputChanged){
				            	dateFromStr=inputDateFrom.value;
				            	dateToStr=inputDateTo.value;

				            	if(dateFromStr.indexOf(\'.\')==-1)
				            		dateFromStr=dateFromStr.replace(/(\d{1,2} [a-zA-Zâêûîôùàçèé]{3})[^ \.]+/,"$1.");
				            	if(dateToStr.indexOf(\'.\')==-1)
				            		dateToStr=dateToStr.replace(/(\d{1,2} [a-zA-Zâêûîôùàçèé]{3})[^ \.]+/,"$1.");

				            	fromDate= Date.parseDate(dateFromStr,"%e %b %Y %H:%M:%S");
				            	toDate= Date.parseDate(dateToStr,"%e %b %Y %H:%M:%S");

				            	if(dateToStr!=\'\'){
					            	if(fromDate>toDate){
	            						inputChanged.value=\'\';
	            						alert(\'' . Mage::helper('headerslider')->__('You must set a date to value greater than the date from value') . '\');
					            	}
				            	}
            				}
            			</script>',
            'disabled' => true
        ));
//        print_r($model->getData());
//        exit();
//        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
