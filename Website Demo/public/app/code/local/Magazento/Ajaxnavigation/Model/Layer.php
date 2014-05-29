<?php

class Magazento_Ajaxnavigation_Model_Layer extends Mage_Catalog_Model_Layer
{
    
    public function getCurrentCategory()
    {
      $category = $this->getData('current_category');
      if (is_null($category)) {
          if ($category = Mage::registry('current_category')) {
              $this->setData('current_category', $category);
          }
          else {
              $category = false;
              $this->setData('current_category', $category);
          }
      }
    
      if(is_null($category) || $category == false) {
          
          $category = Mage::getModel('catalog/category')->load($this->getCurrentStore()->getRootCategoryId());
          $this->setData('current_category', $category);
          Mage::register('current_category', $category);          
      }
    
      return $category;
    }
    
}
