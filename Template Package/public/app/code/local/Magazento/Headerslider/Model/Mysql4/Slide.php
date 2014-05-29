<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Model_Mysql4_Slide extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('headerslider/slide', 'slide_id');
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
        $condition = $this->_getWriteAdapter()->quoteInto('slide_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('headerslider/slide_store'), $condition);
        $this->_getWriteAdapter()->delete($this->getTable('headerslider/slide_product'), $condition);
        if ($object->getData('in_products')) $this->_getWriteAdapter()->delete($this->getTable('headerslider/slide_product'), $condition);
//        var_dump($object->getData('in_products') );
//        exit();

        $products = $object->getData('products');
//        foreach ((array) $products as $product) {
//            if ($product == 0) continue;
//            $productArray = array();
        if ($products) {
            $productArray['slide_id'] = $object->getId();
            $productArray['product_id'] = implode(',',$products);
//        var_dump($productArray);
//        exit();
            $this->_getWriteAdapter()->insert($this->getTable('headerslider/slide_product'), $productArray);
        }


        //STORE
        if (!$object->getData('stores')) {
            $object->setData('stores', $object->getData('store_id'));
        }
        if (in_array(0, $object->getData('stores'))) {
            $object->setData('stores', array(0));
        }
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['slide_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('headerslider/slide_store'), $storeArray);
        }

        return parent::_afterSave($object);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
//        exit();
        //STORE
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('headerslider/slide_store'))
            ->where('slide_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        //PRODUCT
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('headerslider/slide_product'))
            ->where('slide_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $productArray = array();
            foreach ($data as $row) {
                $productArray[] = $row['product_id'];
            }
            $object->setData('product_id', $productArray);
        }
    }

    protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('headerslider/slide_store'), 'slide_id=' . $object->getId());
        $adapter->delete($this->getTable('headerslider/slide_product'), 'slide_id=' . $object->getId());
    }
}