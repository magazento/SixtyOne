<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Headerslider_Model_Slide extends Mage_Core_Model_Abstract
{
    const CACHE_TAG     = 'headerslider_admin_slide';
    protected $_cacheTag= 'headerslider_admin_slide';
    protected function _construct()

    {
        $this->_init('headerslider/slide');
    }

}
