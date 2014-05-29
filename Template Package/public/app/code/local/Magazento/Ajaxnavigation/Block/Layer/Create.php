<?php

class Magazento_Ajaxnavigation_Block_Layer_Create extends Mage_Core_Block_Template
{
    public function getMaxPrice()
    {
        return (int)Mage::getModel('ajaxnavigation/price')->getMaxPriceInt();
    }

    public function getMinPrice()
    {
        return (int)Mage::getModel('ajaxnavigation/price')->getMinPriceInt();
    }
   
    public function isCatalogSearchPage()
    {
        return (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch');
    }
}
