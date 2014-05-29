<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Tabs_Block_Popular extends Mage_Catalog_Block_Product_Abstract {


	protected function _construct() {
		parent::_construct();
		$this->addData(array(
                        'cache_lifetime' => 86400,
                        'cache_tags' => array("magazentotabs_popular" ."_".Mage::app()->getStore()->getId()),
                        'cache_key' => "magazentotabs_popular".'_'.Mage::app()->getStore()->getId(),
		));
	}
        
        protected function _beforeToHtml() {
    	
            $storeId    = Mage::app()->getStore()->getId();
            $products = Mage::getResourceModel('reports/product_collection')
                        ->addOrderedQty()
                        ->addAttributeToSelect('*')
                        ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
                        ->setStoreId($storeId)
                        ->addStoreFilter($storeId)
                        ->addViewsCount();
						
						
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
            $products->setPageSize($this->getData('count'))->setCurPage(1);
			
			//category filter		
            if ($this->getData('categoryid')>0) {
                $category = Mage::getModel("catalog/category")->load($this->getData('categoryid'));
                $products->addCategoryFilter($category); 
            }
							
            $this->setProductCollection($products);
            return parent::_beforeToHtml();
	}
}
    






