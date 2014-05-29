<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Model_Ratings {
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>'1 star'),
            array('value'=>'2', 'label'=>'2 stars'),
            array('value'=>'3', 'label'=>'3 stars'),
            array('value'=>'4', 'label'=>'4 stars'),
            array('value'=>'5', 'label'=>'5 stars')
        );
    }

}
