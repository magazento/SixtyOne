<?php
class Magazento_Ajaxnavigation_Model_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
   protected function _createItem($label, $value, $count=0)
    {
        return Mage::getModel('ajaxnavigation/item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count);
    }

    /**
     * Apply category filter to layer
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    
    protected function setProductIds() {
        if (null !== Mage::registry('current_products_id')) {
            Mage::unregister('current_products_id');
        }
        $count = $this->getLayer()->getProductCollection()->getAllIds();

        Mage::register('current_products_id', $count);
    }

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = (int) $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }
        $this->_categoryId = $filter;

        $category   = $this->getCategory();
        Mage::register('current_category_filter', $category);

        $this->_appliedCategory = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($filter);

        if ($this->_isValidCategory($this->_appliedCategory)) {
            $this->getLayer()->getProductCollection()
                ->addCategoryFilter($this->_appliedCategory);
            
            $this->getLayer()->getState()->addFilter(
                $this->_createItem($this->_appliedCategory->getName(), $filter)
            );
            // FIX           
            $this->setProductIds();
        }

        return $this;
    }
    
    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $categoty   = $this->getCategory();
            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $categoty->getChildrenCategories();

            $this->getLayer()->getProductCollection()
                ->addCountToCategories($categories);

            $data = array();
            foreach ($categories as $category) {
                if ($category->getIsActive() && $category->getProductCount()) {
                    $count = $category->getProductCount();
                    $newCount = array();
                    if (Mage::registry('current_products_id')) {
                        $newCount = Mage::registry('current_products_id');
                    }
                    
                    if ($count == count($newCount)) {
                        $count = 0;
                    }
                    if ($count > 0) {
                        $data[] = array(
                            'label' => Mage::helper('core')->htmlEscape($category->getName()),
                            'value' => $category->getId(),
                            'count' => $count,
                        );
                    }
                }
            }
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
    
    public function getResetValue($currentValue = null, $varName = null)
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
            $oldQuery[$var_name] = $value;
        } else {
            $query = array(
                $var_name => $value,
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls,
            );
        }
        $result = array_merge($query, $oldQuery);

        return $result;        
    }

}
