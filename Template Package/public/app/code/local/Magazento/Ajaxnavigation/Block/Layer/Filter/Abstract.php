<?php

abstract class Magazento_Ajaxnavigation_Block_Layer_Filter_Abstract extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    /**
     * Catalog Layer Filter Attribute model
     *
     * @var Mage_Catalog_Model_Layer_Filter_Attribute
     */
    protected $_filter;

    /**
     * Filter Model Name
     *
     * @var string
     */
    protected $_filterModelName;

    /**
     * Initialize filter template
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('magazento_ajaxnavigation/layer/filter.phtml');
    }

    /**
     * Retrieve filter items count
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->_filter->getItemsCount();
    }
    
}
