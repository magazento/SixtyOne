<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Tabs_Model_Source_Align {

    public function toOptionArray() {
        return array(
            array('value' => 'left', 'label' => Mage::helper('tabs')->__('Left')),
            array('value' => 'right','label' => Mage::helper('tabs')->__('Right')),

        );
    }

}