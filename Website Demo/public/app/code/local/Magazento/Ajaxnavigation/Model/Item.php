<?php

class Magazento_Ajaxnavigation_Model_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        $query = $this->getFilter()->getUrlValue($this->getValue(), $this->getFilter()->getRequestVar());

        $currentCategory = Mage::registry('current_category');
        $currentCategoryId = $currentCategory->getId();
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
        
        if ((int)$currentCategoryId) {
            $urlPath = $currentCategory->getUrlPath();
            $url = Mage::getUrl($urlPath, array(
                '_query' => $query
            ));
        } 
        
        if (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch') {
            $oldQuery = array();
            $currentUrl = Mage::helper('core/url')->getCurrentUrl();
            $queryString = parse_url($currentUrl);
            
            $oldQuery = $this->convertUrlQuery($queryString['query']);
            $newQuery = array_merge($query, $oldQuery);
            $urlPath = 'catalogsearch/result/index/';
            $url = Mage::getUrl($urlPath, array(
                '_query' => $newQuery
            ));
        } 
        $url = str_replace(array('/?', '.html/'), array('?', '.html'), $url);
        return $url;
    }
    
    public function getRemoveUrl()
    {
        $currentValue = $this->getValue();
        $query = $this->getFilter()->getResetValue($currentValue, $this->getFilter()->getRequestVar());
        
        $currentCategory = Mage::registry('current_category');
        
        $currentCategoryId = $currentCategory->getId();
        $rootCategoryId = Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId();
        
        if ((int)$currentCategoryId) {
            $urlPath = $currentCategory->getUrlPath();
            $url = Mage::getUrl($urlPath, array(
                '_query' => $query
            ));
            
        } 
        
        if (Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch') {
            $oldQuery = array();
            $currentUrl = Mage::helper('core/url')->getCurrentUrl();
            $queryString = parse_url($currentUrl);
            
            $oldQuery = $this->convertUrlQuery($queryString['query']);
            $newQuery = array_merge($query, $oldQuery);
            $urlPath = 'catalogsearch/result/index';
            $url = Mage::getUrl($urlPath, array(
                '_query' => $newQuery
            ));
        } 
        
        $url = str_replace(array('/?', '.html/'), array('?', '.html'), $url); 
        return $url;
    }
    
    public function convertUrlQuery($query) {
        $queryParts = explode('&', $query);
        
        $params = array();
        foreach ($queryParts as $param) {
            
            $item = explode('=', $param);
            if ($item[0] == 'q' || $item[0] == 'x' || $item[0] == 'y') {
                $params[$item[0]] = $item[1];
            }
        }
       
        return $params;
    } 
}
