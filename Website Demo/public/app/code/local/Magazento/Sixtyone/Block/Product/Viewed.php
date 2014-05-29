<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Block_Product_Viewed extends Mage_Reports_Block_Product_Viewed
{
    public function getPageSize()
    {
        return 20;
    }

}
