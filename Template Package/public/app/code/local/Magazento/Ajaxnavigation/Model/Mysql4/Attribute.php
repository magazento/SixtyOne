<?php

class Magazento_Ajaxnavigation_Model_Mysql4_Attribute extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
{
    /**
     * Initialize connection and define main table name
     *
     */
    protected function _construct()
    {
        $this->_init('catalog/product_index_eav', 'entity_id');
    }

    /**
     * Apply attribute filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @param int $value
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
     */
    public function applyFilterToCollection($filter, $value)
    {
        /**
         * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection 
         */
        $collection = $filter->getLayer()->getProductCollection();
        $tmpCollection = clone $collection;
        
        $attribute  = $filter->getAttributeModel();
        
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            $connection->quoteInto("{$tableAlias}.value in (?)", $value)
        );
        

        $collection->getSelect()->join(
            array($tableAlias => $this->getMainTable()),
            join(' AND ', $conditions),
            array()
        );

        $collection->getSelect()->distinct();
        
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

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @return array
     */
    public function getCount($filter)
    {
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
		$currentProducsId = array();
		$currentAttributeId = array();
		if (is_array(Mage::registry('current_products_id'))) {
		    $currentProducsId = Mage::registry('current_products_id');
		}
		if (is_array(Mage::registry('current_attribute_id'))) {
		    $currentAttributeId = Mage::registry('current_attribute_id');
		}        
        
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute  = $filter->getAttributeModel();
        $query = Mage::registry('query_request');
        $code = $attribute->getAttributeCode();
        $plus = false;
        if (array_key_exists($code, $query)) {
            $plus = true;
        }
        $attributeId = $attribute->getAttributeId();
        $tableAlias = $attribute->getAttributeCode() . '_idx1';
        
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        );
        $filters = $filter->getCurrentFilter();
        
        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array("{$tableAlias}.value", 'entity' => "{$tableAlias}.entity_id"))
            ->where("{$tableAlias}.value not IN (?)", $filters)
            // ->where("{$tableAlias}.entity_id not IN (?)", $currentProducsId)
            ;
            
        $from = $select->getPart('FROM');
        $selectString = strtolower($select->__toString());
        if (!empty($from[$attribute->getAttributeCode() . '_idx'])) {
            $joinData = $from[$attribute->getAttributeCode() . '_idx'];
            $remove = strtolower(sprintf(
                "%s `%s` AS `%s` ON %s", 
                $joinData['joinType'],
                $joinData['tableName'],
                $attribute->getAttributeCode() . '_idx',
                $joinData['joinCondition']
            ));
        
            $selectString = str_replace($remove, '', $selectString);
        }  
        
        $newId = $connection->fetchAll($selectString);
        $result = array();
        if (in_array((int)$attributeId, $currentAttributeId)) {
            foreach ($newId as $attr) {
                $value = $attr['value'];
                $entity = $attr['entity'];
                $existProduct = false;
                foreach ($currentProducsId as $id) {
                    if ((int)$entity == (int)$id) {
                        $existProduct = true;
                    }
                }
                if (!$existProduct) {
                    if (array_key_exists($value, $result)) {
                        $result[$value] += 1; 
                    } else {
                        $result[$value] = 1;
                    }
                }
            }
        } else {
            foreach ($newId as $attr) {
                $value = $attr['value'];
                $entity = $attr['entity'];
                if (array_key_exists($value, $result)) {
                    $result[$value] += 1; 
                } else {
                    $result[$value] = 1;
                }
            }
            
            foreach ($result as $value => $count) {
                if ($count == count($currentProducsId)) {
                    $result[$value] = 0;
                } else {
                    if ($plus && $count < $currentProducsId) {
                        $result[$value] = 0;
                    }
                }
            }
        }
        
        return $result;
        
    }

}
