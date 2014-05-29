<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
Class Magazento_Tabs_Model_Data
{

    protected function getTabModel() {
        return Mage::getModel('tabs/tab');
    }
    protected function getTabCollection() {
        $storeId = Mage::app()->getStore()->getId();
        $collection = $this->getTabModel()->getCollection();
        $collection->addFilter('is_active', 1);
        $collection->addStoreFilter($storeId);
        $collection->addOrder('position', 'ASC');
        return $collection;
    }
    public function getTabs() {
        return $this->getTabCollection();
    }



}