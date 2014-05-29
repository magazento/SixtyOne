<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Tabs_Model_Source_Menuskin {

    public function toOptionArray() {
        return array(
            array('value' => 'menuredtabs', 'label' => Mage::helper('tabs')->__('Redtabs')),
            array('value' => 'menured', 'label' => Mage::helper('tabs')->__('Red')),
            array('value' => 'menublue','label' => Mage::helper('tabs')->__('Blue')),
        );
    }

}