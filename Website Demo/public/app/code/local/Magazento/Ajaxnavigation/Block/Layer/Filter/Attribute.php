<?php

class Magazento_Ajaxnavigation_Block_Layer_Filter_Attribute extends Magazento_Ajaxnavigation_Block_Layer_Filter_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('magazento_ajaxnavigation/layer/filter.phtml');
        $this->_filterModelName = 'ajaxnavigation/attribute';
    }

    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());
        return $this;
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
