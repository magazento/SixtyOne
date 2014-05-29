<?php

class Magazento_Ajaxnavigation_Block_Layer_View extends Mage_Core_Block_Template
{
    /**
     * Get attribute filter block name
     *
     * @return string
     */
    protected function _getAttributeFilterBlockName()
    {
        return 'ajaxnavigation/layer_filter_attribute';
    }

    /**
     * Prepare child blocks
     *
     * @return Mage_Catalog_Block_Layer_View
     */
    protected function _prepareLayout()
    {
        $stateBlock = $this->getLayout()->createBlock('ajaxnavigation/layer_state')
            ->setLayer($this->getLayer());

        $categryBlock = $this->getLayout()->createBlock('ajaxnavigation/layer_filter_category')
            ->setLayer($this->getLayer())
            ->init();

        $this->setChild('layer_state', $stateBlock);
        $this->setChild('ajaxnavigation_filter', $categryBlock);

        $filterableAttributes = $this->_getFilterableAttributes();
       
        
        foreach ($filterableAttributes as $attribute) {
            $filterBlockName = $this->_getAttributeFilterBlockName();
//            var_dump($filterBlockName);
            if ($attribute->getAttributeCode() == 'price') {
                $filterBlockName = 'ajaxnavigation/layer_filter_price';
            } else if ($attribute->getBackendType() == 'decimal') {
                $filterBlockName = 'ajaxnavigation/layer_filter_decimal';
            }

            $this->setChild($attribute->getAttributeCode().'_filter',
                $this->getLayout()->createBlock($filterBlockName)
                    ->setLayer($this->getLayer())
                    ->setAttributeModel($attribute)
                    ->init());
        }
//        exit();

        $this->getLayer()->apply();
        return parent::_prepareLayout();
    }

    public function getLayer()
    {
        return Mage::getSingleton('catalog/layer');
    }

    /**
     * Get all fiterable attributes of current category
     *
     * @return array
     */
    protected function _getFilterableAttributes()
    {
        $attributes = $this->getData('_filterable_attributes');
        if (is_null($attributes)) {
            $attributes = $this->getLayer()->getFilterableAttributes();
            $this->setData('_filterable_attributes', $attributes);
        }

        return $attributes;
    }

    /**
     * Get layered navigation state html
     *
     * @return string
     */
    public function getStateHtml()
    {
        return $this->getChildHtml('layer_state');
    }

    /**
     * Get all layer filters
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = array();
        if ($categoryFilter = $this->_getCategoryFilter()) {
            $filters[] = $categoryFilter;
        }

        $filterableAttributes = $this->_getFilterableAttributes();

        foreach ($filterableAttributes as $attribute) {
            $filters[] = $this->getChild($attribute->getAttributeCode().'_filter');
        }

        return $filters;
    }

    /**
     * Get category filter block
     *
     * @return Mage_Catalog_Block_Layer_Filter_Category
     */
    protected function _getCategoryFilter()
    {
        return $this->getChild('ajaxnavigation_filter');
    }

    /**
     * Check availability display layer options
     *
     * @return bool
     */
    public function canShowOptions()
    {
        foreach ($this->getFilters() as $filter) {
            if ($filter->getItemsCount()) {
                return true;
    }
        }
        return false;
    }

    /**
     * Check availability display layer block
     *
     * @return bool
     */
    public function canShowBlock()
    {
        return $this->canShowOptions() || count($this->getLayer()->getState()->getFilters());
    }

    /**
     * Retrieve Price Filter block
     *
     * @return Mage_Catalog_Block_Layer_Filter_Price
     */
    protected function _getPriceFilter()
    {
        return $this->getChild('_price_filter');
    }
    
}