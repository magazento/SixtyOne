<?php

class Magazento_Ajaxnavigation_Block_Layer_State extends Mage_Catalog_Block_Layer_State
{
    /**
     * Initialize Layer State template
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('magazento_ajaxnavigation/layer/state.phtml');
    }

    /**
     * Retrieve active filters
     *
     * @return array
     */
    public function getActiveFilters()
    {
        $filters = $this->getLayer()->getState()->getFilters();
        if (!is_array($filters)) {
            $filters = array();
        }
        return $filters;
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {
        $filterState = array();
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $filterState;
        $params['_escape']      = true;
        
        return Mage::getUrl('*/*/*', $params);
    }

    /**
     * Retrieve Layer object
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        if (!$this->hasData('layer')) {
            $this->setLayer(Mage::getSingleton('catalog/layer'));
        }
        
        return $this->_getData('layer');
    }
}
