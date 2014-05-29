<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Tabs_Block_Info extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function __construct() {
        parent::__construct();
    }
    public function render(Varien_Data_Form_Element_Abstract $element) {
        $html = $this->_getHeaderHtml($element);
        $html.= $this->_getFieldHtml($element);
        $html .= $this->_getFooterHtml($element);
        return $html;
    }
    protected function _getFieldHtml($fieldset) {
        $content = 'This extension is developed by <a href="http://www.magazento.com/" target="_blank">www.magazento.com</a><br/>';
        $content.= 'Magento Store Setup, modules, data migration, templates, upgrades and much more!';
        return $content;
    }

}
