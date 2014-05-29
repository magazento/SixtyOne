<?php

class Magazento_Ajaxnavigation_Model_Mysql4_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
{

    
    /**
     * Retrieve array with products counts per price range
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $range
     * @return array
     */
    public function getCount($filter=null, $range)
    {
        $fromTo = explode('-', $range);
        $from = $fromTo[0];
        $to = $fromTo[1];
        $select = $this->_getSelect($filter);
        $currentProducsId = Mage::registry('current_products_id');
        $currentAttributeId = Mage::registry('current_attribute_id');
        $state = $filter->getLayer()->getState()->getFilters();
        
        $stateCount = count($state);
        foreach ($state as $item) {
            $value = explode(',', $item->getValue());
            if (count($value) == 2) {
                if (($value[0] == $from) && ($value[1] == $to)) {
                    return 0;
                }
            }
        }
        
        $attribute  = $filter->getAttributeModel();
        $query = Mage::registry('query_request');
        $code = $attribute->getAttributeCode();
        $plus = false;
        if (array_key_exists($code, $query)) {
            $plus = true;
        }
        $attributeId = $attribute->getAttributeId();
        
        $connection = $this->_getReadAdapter();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table = $this->_getIndexTableAlias();

        $additional = join('', $response->getAdditionalCalculations());
        $rate       = $filter->getCurrencyRate();
        $priceExpr  = new Zend_Db_Expr("(({$table}.min_price {$additional}) * {$rate})");
        $select->columns(array($priceExpr));
        
        if ($stateCount == 0) {
            $select
            ->where($priceExpr . ' >= ?', $from)
            ->where($priceExpr . ' < ?', $to);
            
            $newId = $connection->fetchAll($select);
            $result = count($connection->fetchAll($select));
        } else {
            $lastCount = count($connection->fetchAll($select));
            $where = $this->_getReadAdapter()->quoteInto("{$priceExpr} >= ?", $from)
                . ' AND '
                . $this->_getReadAdapter()->quoteInto("{$priceExpr} <= ?", $to);

            $select->orWhere($where);
            $newId = $connection->fetchAll($select);
            if (null === $filter->getData('request')) {
                $result = count($connection->fetchAll($select));
            } else {
                $result = count($connection->fetchAll($select)) - $lastCount;
            }
            
        }
        if (count($currentProducsId) == count($result)) {
            $result = 0;
        }
        return  $result;
    }

    /**
     * Apply attribute filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $range
     * @param int $index    the range factor
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
     */
    public function applyFilterToCollection($filter, $price, $index)
    {
        $collection = $filter->getLayer()->getProductCollection();
        
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());
        $select     = $collection->getSelect();
        $attribute  = $filter->getAttributeModel();
        
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table      = $this->_getIndexTableAlias();
        $additional = join('', $response->getAdditionalCalculations());
        $rate       = $filter->getCurrencyRate();
        $priceExpr  = new Zend_Db_Expr("(({$table}.min_price {$additional}) * {$rate})");

        $where = array();
        foreach ($price as $value) {
            $data = explode(',', $value);
            list($from, $to) = $data;

            $where[] = $this->_getReadAdapter()->quoteInto("{$priceExpr} >= ?", $from)
                . ' AND '
                . $this->_getReadAdapter()->quoteInto("{$priceExpr} <= ?", $to);
        }
        if (count($where)) {
            $select->where(implode(' OR ', $where));
        }
        
        $connection = $this->_getReadAdapter();
        
        $result = $connection->fetchAll($collection->getSelect());
        
        $productIds = array();
        foreach ($result as $entity) {
            $productIds[] = $entity['entity_id'];
        }
        
        if (null !== Mage::registry('current_products_id')) {
            Mage::unregister('current_products_id');
        }
        
		if (null !== Mage::registry('current_attribute_id')) {
            
            if (is_array(Mage::registry('current_attribute_id'))) {
                $currentAttributeId = Mage::registry('current_attribute_id');
                $currentAttributeId[] = $attribute->getAttributeId();
            } else {
                $currentAttributeId = array();
                $currentAttributeId[] = Mage::registry('current_attribute_id');
                $currentAttributeId[] = $attribute->getAttributeId();
            }
            Mage::unregister('current_attribute_id');
            Mage::register('current_attribute_id', $currentAttributeId);
        } else {
            Mage::register('current_attribute_id', array($attribute->getAttributeId()));
        }
		
        Mage::register('current_products_id', $productIds);
        
        return $this;
    }
    
    protected function _getSelect($filter)
    {
        if (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch') {
            $layer = $this->getCatalogSearchLayer();
            $collection = $layer->getProductCollection();
        } else {
            $collection = $filter->getLayer()->getProductCollection();
        }
        
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

        // clone select from collection with filters
        $select = clone $collection->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        return $select;
    }
    
    public function getMaxPrice($filter)
    {
        $select = $this->_getSelect($filter);
        $connection = $this->_getReadAdapter();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table = $this->_getIndexTableAlias();

        $additional   = join('', $response->getAdditionalCalculations());
        $maxPriceExpr = new Zend_Db_Expr("MAX({$table}.min_price {$additional})");

        $select->columns(array($maxPriceExpr));

        return $connection->fetchOne($select) * $filter->getCurrencyRate();
    }

    public function getCatalogSearchLayer()
    {
        return Mage::getSingleton('ajaxnavigation/catalogsearch_layer');
    }

    public function getMinPrice($filter)
    {
        $select = $this->_getSelect($filter);
        $connection = $this->_getReadAdapter();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table = $this->_getIndexTableAlias();

        $additional     = join('', $response->getAdditionalCalculations());
        $maxPriceExpr   = new Zend_Db_Expr("MIN({$table}.min_price {$additional})");

        $select->columns(array($maxPriceExpr));

        return $connection->fetchOne($select) * $filter->getCurrencyRate();
    }

    
}
