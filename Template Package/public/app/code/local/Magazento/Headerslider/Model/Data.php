<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
Class Magazento_Headerslider_Model_Data {

    
    protected function getSlideModel() {
        return Mage::getModel('headerslider/slide');
    }

    protected function getItemCollection() {
        $collection = $this->getSlideModel()->getCollection();
        $collection->addStoreFilter(Mage::app()->getStore()->getId());
        $collection->addProducts();
        $collection->addFilter('is_active', 1);
        $collection->addNowFilter();
        $collection->addOrder('position', 'ASC');
        return $collection;
    }

    public function getItems() {
        return $this->getItemCollection();
    }

    public function hasItems() {

        return $this->getItemCollection()->count();
    }

}