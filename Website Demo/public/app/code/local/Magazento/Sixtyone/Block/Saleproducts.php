<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Block_Saleproducts extends Mage_Catalog_Block_Product_List implements Mage_Widget_Block_Interface
{
    protected $products;

    protected function _construct() {
        parent::_construct();
    }

    public function getProductsAmount () {
        return $this->getData('products_amount');
    }
    public function getStaticBlock () {
        return $this->getData('static_block');
    }

    public function getProductsCollection () {
        
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
            ->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
            ->addAttributeToSelect('special_price')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'special_price', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )        
            ->addAttributeToSelect('status')
            ->addStoreFilter()
//            ->addCategoryFilter(Mage::getModel('catalog/category')->load('#');
        ->setPageSize($this->getProductsAmount())
        ->setCurPage(1);        

        return $collection;
    }

}
