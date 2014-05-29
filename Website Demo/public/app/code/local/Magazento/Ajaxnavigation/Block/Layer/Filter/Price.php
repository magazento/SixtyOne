<?php
class Magazento_Ajaxnavigation_Block_Layer_Filter_Price extends Magazento_Ajaxnavigation_Block_Layer_Filter_Abstract
{
    /**
     * Initialize Price filter module
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('magazento_ajaxnavigation/layer/price.phtml');
        $this->_filterModelName = 'ajaxnavigation/price';

    }

    /**
     * Prepare filter process
     *
     * @return Mage_Catalog_Block_Layer_Filter_Price
     */
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        return $this;
    }
    public function getCategoryId()
    {
        $layer = Mage::getSingleton('catalog/layer')->getCurrentCategory();

        return $layer->getId();
    }

    public function getMaxPrice()
    {
        return (int)Mage::getModel('ajaxnavigation/price')->getMaxPriceInt();
    }

    public function getMinPrice()
    {
        return (int)Mage::getModel('ajaxnavigation/price')->getMinPriceInt();
    }
    
    public function getParamUrl()
    {
        return Mage::helper('core/url')->getCurrentUrl();;
    }
    
    public function getActiveFilter()
    {
        $attribute = $this->getAttributeModel();
        
        $query = Mage::registry('query_request');
        $code = $attribute->getAttributeCode();
        $plus = false;
        if (array_key_exists($code, $query)) {
            $plus = true;
        }
        
        return $plus;
    }
}
