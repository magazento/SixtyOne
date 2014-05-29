<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Tabs_Model_Tab extends Mage_Core_Model_Abstract
{
    const CACHE_TAG     = 'tabs_admin_tab';
    protected $_cacheTag= 'tabs_admin_tab';

    protected function _construct()
    {
        $this->_init('tabs/tab');


    }

}
