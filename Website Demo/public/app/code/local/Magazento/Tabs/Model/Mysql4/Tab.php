<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
?>
<?php

class Magazento_Tabs_Model_Mysql4_Tab extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('tabs/tab', 'tab_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        if (!$object->getFromTime()) {
            $object->setFromTime(Mage::getSingleton('core/date')->gmtDate());
        } else {
            $object->setFromTime(Mage::app()->getLocale()->date($object->getFromTime(), $dateFormatIso));
            $object->setFromTime($object->getFromTime()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $object->setFromTime(Mage::getSingleton('core/date')->gmtDate(null, $object->getFromTime()));
        }
        if (!$object->getToTime()) {
            $object->setToTime();
        } else {
            $object->setToTime(Mage::app()->getLocale()->date($object->getToTime(), $dateFormatIso));
            $object->setToTime($object->getToTime()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
            $object->setToTime(Mage::getSingleton('core/date')->gmtDate(null, $object->getToTime()));
        }
        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object) {
        $condition = $this->_getWriteAdapter()->quoteInto('tab_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('tabs/tab_store'), $condition);
        // $this->_getWriteAdapter()->delete($this->getTable('tabs/tab_item'), $condition);
        // $this->_getWriteAdapter()->delete($this->getTable('tabs/tab_category'), $condition);
		
        if (!$object->getData('stores')) {
            $object->setData('stores', $object->getData('store_id'));
        }
        if (in_array(0, $object->getData('stores'))) {
            $object->setData('stores', array(0));
        }
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['tab_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('tabs/tab_store'), $storeArray);
        }
		
		
        // if (!$object->getData('items')) {
            // $object->setData('items', $object->getData('item_id'));
        // }
        // if (in_array(0, $object->getData('items'))) {
            // $object->setData('items', array(0));
        // }
        // foreach ((array) $object->getData('items') as $store) {
            // $storeArray = array();
            // $storeArray['tab_id'] = $object->getId();
            // $storeArray['item_id'] = $store;
            // $this->_getWriteAdapter()->insert($this->getTable('tabs/tab_item'), $storeArray);
        // }
// 		
        // if (!$object->getData('categories')) {
            // $object->setData('categories', $object->getData('category_id'));
        // }
        // if (in_array(0, $object->getData('categories'))) {
            // $object->setData('categories', array(0));
        // }
        // foreach ((array) $object->getData('categories') as $store) {
            // $storeArray = array();
            // $storeArray['tab_id'] = $object->getId();
            // $storeArray['category_id'] = $store;
            // $this->_getWriteAdapter()->insert($this->getTable('tabs/tab_category'), $storeArray);
        // }
		
		
		
        return parent::_afterSave($object);
    }
	
    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        	
			
        $select = $this->_getReadAdapter()->select()
                        ->from($this->getTable('tabs/tab_store'))
                        ->where('tab_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }
		
		
// 
        // $select = $this->_getReadAdapter()->select()
                        // ->from($this->getTable('tabs/tab_item'))
                        // ->where('tab_id = ?', $object->getId());
        // if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            // $storesArray = array();
            // foreach ($data as $row) {
                // $storesArray[] = $row['item_id'];
            // }
            // $object->setData('item_id', $storesArray);
        // }
// 
		// $select = $this->_getReadAdapter()->select()
                        // ->from($this->getTable('tabs/tab_category'))
                        // ->where('tab_id = ?', $object->getId());
        // if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            // $storesArray = array();
            // foreach ($data as $row) {
                // $storesArray[] = $row['category_id'];
            // }
            // $object->setData('category_id', $storesArray);
        // }
				
		
		
        return parent::_afterLoad($object);
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('tabs/tab_store'), 'tab_id=' . $object->getId());
    }

}