<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

Class Magazento_Headerslider_Block_Headerslider extends Mage_Core_Block_Template {

    public function getSlides() {
        $collection = Mage::getModel('headerslider/data')->getItems();
        $slidesArray = array();
        foreach ($collection as $k => $_item) {
            $slidesArray[$k] = $_item->getData();
            $slidesArray[$k]['products'] = array();
            $products = explode(',',$_item['product_id']);
            foreach ($products as $_productId) {
                $_product = Mage::getModel('catalog/product')->load($_productId);
                if ($_product->getId()) {
                    $slidesArray[$k]['products'][] = $_product;
                }
            }
        }
//        var_dump($slidesArray);
//        exit();
        return $slidesArray;
    }

}