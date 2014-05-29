<?php
$controller = BP . DS . 'app' . DS . 'code' . DS . 'core' . DS . 'Mage' . DS . 'Catalog' . DS . 'controllers' . DS . 'CategoryController.php';
require_once $controller;

class Magazento_Ajaxnavigation_CategoryController extends Mage_Catalog_CategoryController
{
    public function viewAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return parent::viewAction();
        }
        $this->_processJson();
    }
    
    function _processJson() {
        
        $query = array();
        $query = $this->getRequest()->getQuery();
        Mage::register('query_request', $query);
        
        $this->_initCatagory();
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('catalog_category_layered');
        $layout->generateXml();
        $layout->generateBlocks();
        
        $result = array();
        $result['list'] = $layout->getBlock('product_list')->toHtml();
        $result['filter'] = $layout->getBlock('ajaxnavigation.catalog.leftnav')->toHtml();
        
        
        
        $priceMin = (int)Mage::getModel('ajaxnavigation/price')->getMinPriceInt();
        if ($this->getRequest()->getParam('price')) {
            $priceMin = explode(',', $this->getRequest()->getParam('price'));
            $priceMin = $priceMin[0];
        }
        $priceMax = (int)Mage::getModel('ajaxnavigation/price')->getMaxPriceInt();
        if ($this->getRequest()->getParam('price')) {
            $priceMax = explode(',', $this->getRequest()->getParam('price'));
            $priceMax = $priceMax[1];
        }
        $result['minPrice'] = $priceMin;
        $result['maxPrice'] = $priceMax;
        $result['categoryName'] = '<div class="page-title category-title"> <h1>' 
            . Mage::registry('current_category')->getName() . '</h1></div>';
        
        $this->getResponse()->setBody(Zend_Json::encode($result));        
        
    }
    
    
}
