<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Sixtyone_Block_Info extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {

        $html = $this->_getHeaderHtml($element);

        $html.= $this->_getFieldHtml($element);

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    protected function _getFieldHtml($fieldset) {
        $content = 'This theme is developed by <a href="http://www.magazento.com/" target="_blank">www.magazento.com</a><br/>';
        $content.= 'Magento Store Setup, modules, data migration, templates, upgrades and much more!<br/>';
        $content.= 'If you need any help using this theme, please send us a quote at <a href="http://www.magazento.com/english/quote">http://www.magazento.com/english/quote</a> or email us to service@magazento.com. All services are paid.<br/>';
        return $content;
    }

}
