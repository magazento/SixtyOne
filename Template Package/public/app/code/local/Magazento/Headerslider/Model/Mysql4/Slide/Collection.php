<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Model_Mysql4_Slide_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        $this->_init('headerslider/slide');
    }

    public function toOptionArray() {
        return $this->_toOptionArray('slide_id', 'name');
    }

    public function addNowFilter() {
        $now = Mage::getSingleton('core/date')->gmtDate();
        $where = "from_time < '" . $now . "' AND ((to_time > '" . $now . "') OR (to_time IS NULL))";
        $this->getSelect()->where($where);
    }

    public function addStoreFilter($store, $withAdmin = true) {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        $this->getSelect()->join(
            array('slide_store' => $this->getTable('headerslider/slide_store')),
            'main_table.slide_id = slide_store.slide_id',
            array('slide_store.*')
        )
            ->where('slide_store.store_id in (?)', ($withAdmin ? array(0, $store) : $store));

        return $this;
    }
    public function addProducts() {

        $this->getSelect()->joinLeft(
            array('slide_product' => $this->getTable('headerslider/slide_product')),
            'main_table.slide_id = slide_product.slide_id',
            array('slide_product.*')
        );
//            ->distinct()
//            ->where('slide_product.product_id in (?) OR main_table.assign_products = 1', $product);

        return $this;
    }

}