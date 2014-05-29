<?php

class Magazento_Ajaxnavigation_Model_Price extends Mage_Catalog_Model_Layer_Filter_Price
{
    /**
     * Create filter item object
     *
     * @param   string $label
     * @param   mixed $value
     * @param   int $count
     * @return  Mage_Catalog_Model_Layer_Filter_Item
     */
    protected function _createItem($label, $value, $count=0, $active=0)
    {
        return Mage::getModel('ajaxnavigation/item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count)
            ->setActive($active);
    }
    
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $items[] = $this->_createItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
                $itemData['active']
            );
        }
        $this->_items = $items;
        return $this;
    }
    
    /**
     * Resource instance
     *
     * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
     */
    protected $_resource;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'price';
    }

    /**
     * Retrieve resource instance
     *
     * @return Magazento_Ajaxnavigation_Model_Mysql4_Price
     */

    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('ajaxnavigation/price');
        }
        return $this->_resource;
    }

    /**
     * Get maximum price from layer products set
     *
     * @return float
     */
    public function getMaxPriceInt()
    {
        $maxPrice = $this->getData('max_price_int');
        
        if (is_null($maxPrice)) {
            $maxPrice = $this->_getResource()->getMaxPrice($this);
            $maxPrice = ceil($maxPrice);
            $this->setData('max_price_int', $maxPrice);
        }
        return $maxPrice;
    }

    /**
     * Get minimum price from layer products set
     *
     * @return float
     */
    public function getMinPriceInt()
    {
        $minPrice = $this->getData('min_price_int');
        if (is_null($minPrice)) {
            $minPrice = $this->_getResource()->getMinPrice($this);
            $minPrice = floor($minPrice);
            $this->setData('min_price_int', $minPrice);
        }
        return $minPrice;
    }

    /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    protected function _renderItemLabel($index, $value)
    {
        $store      = Mage::app()->getStore();
        
        $index1 = explode('-', $index);
        $fromPrice  = $store->formatPrice($index1[0]);
        $toPrice    = $store->formatPrice($index1[1]);
        return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
    }

    /**
     * Get price aggreagation data cache key
     *
     * @return string
     */
    protected function _getCacheKey()
    {
        $key = $this->getLayer()->getStateKey()
            . '_PRICES_GRP_' . Mage::getSingleton('customer/session')->getCustomerGroupId()
            . '_CURR_' . Mage::app()->getStore()->getCurrentCurrencyCode()
            . '_ATTR_' . $this->getAttributeModel()->getAttributeCode()
            . '_LOC_'
            ;
        $taxReq = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $key.= implode('_', $taxReq->getData());
        return $key;
    }

    /**
     * Get data for build price filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $key = $this->_getCacheKey();

        $data = $this->getLayer()->getAggregator()->getCacheData($key);
        if ($data === null) {
            $category = Mage::getSingleton('catalog/layer')->getCurrentCategory();
//            $range = $this->getPriceRange();
//            $dbRanges   = $this->getRangeItemCounts($category->getId());
//            print_r($dbRanges);die;
            $data       = array();
            $oldQuery = array();
            if (Mage::registry('query_request')) {
                $oldQuery = Mage::registry('query_request');
            } elseif (Mage::registry('root_request')) {
                $oldQuery = Mage::registry('root_request');
            }
            $query = array();
            if (array_key_exists($this->_requestVar, $oldQuery)) {
                $query = explode('-', $oldQuery[$this->_requestVar]);
            }
            
            $tags = array(
                Mage_Catalog_Model_Product_Type_Price::CACHE_TAG,
            );
            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }

    /**
     * Apply price range filter to collection
     *
     * @return Mage_Catalog_Model_Layer_Filter_Price
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        /**
         * Filter must be string: $index,$range
         */
        $filter = $request->getParam($this->getRequestVar());
        $this->setData('request', $filter);
        if (!$filter) {
            return $this;
        }

        $price = array();
        $filter = explode('-', $filter);
        if (count($filter) > 1) {
            foreach ($filter as $value) {
                $price[] = $value;
            }
        } else {
            $price = $filter;
        }

        if (count($price) < 1) {
            return $this;
        }

        $this->_getResource()->applyFilterToCollection($this, $price, null);

        foreach ($price as $value) {
            list($from, $to) = explode(',', $value);
            $this->getLayer()->getState()->addFilter(
                $this->_createItem($this->_renderItemLabel($from . ' - ' . $to, null), $value)
            );
            $this->getLayer()->getState()->setCurrentValue($value);
        }
        
//        $categoryId = Mage::getSingleton('catalog/layer')->getCurrentCategory()->getId();
//        $result = $this->_getResource()->getRanges($categoryId, null);
//        if (is_null($result)) {
        $this->_items = array();
//        }
    }
    
    public function getResetValue($currentValue=null, $varName=null)
    {
        $result = array();
        $oldQuery = array();
        
        if (Mage::registry('query_request')) {
            $oldQuery = Mage::registry('query_request');
        }

        if (array_key_exists($varName, $oldQuery)) {
            if (count(explode('-', $oldQuery[$varName])) > 1) {
                $oldQuery[$varName] = str_replace(array('-' . $currentValue, $currentValue . '-'), array('', ''), $oldQuery[$varName]);
            } else {
                $oldQuery[$varName] = null;
            }
        }
        $result = $oldQuery;
        
        return $result;    
    }
    
    public function getUrlValue($value, $var_name)
    {
        $result = array();
        $oldQuery = array();
        $query = array();
        
        if (Mage::registry('query_request')) {
            $oldQuery = Mage::registry('query_request');
        }
        
        if (array_key_exists($var_name, $oldQuery)) {
            $oldQuery[$var_name] .= '-' . $value;
        } else {
            $query = array(
                $var_name => $value,
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls,
            );
        }
        $result = array_merge($query, $oldQuery);

        return $result;
        
    }
    
    
    public function getItemsCount()
    {
        return 1;
    }
    
}