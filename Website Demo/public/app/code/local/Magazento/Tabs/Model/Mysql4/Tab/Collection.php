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

class Magazento_Tabs_Model_Mysql4_Tab_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        $this->_init('tabs/tab');
    }

    public function toOptionArray() {
        return $this->_toOptionArray('tab_id', 'name');
    }
    
    public function addStoreFilter($store, $withAdmin = true) {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        $this->getSelect()->join(
                        array('tab_store' => $this->getTable('tabs/tab_store')),
                        'main_table.tab_id = tab_store.tab_id',
                        array()
                )
                ->where('tab_store.store_id in (?)', ($withAdmin ? array(0, $store) : $store));

        return $this;
    }
    public function addNowFilter() {
        $now = Mage::getSingleton('core/date')->gmtDate();
        $where = "from_time < '" . $now . "' AND ((to_time > '" . $now . "') OR (to_time IS NULL))";
        $this->getSelect()->where($where);
    }

}