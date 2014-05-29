<?php

class Magazento_Ajaxnavigation_Model_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    
    protected function _createItem($label, $value, $count=0, $active = 0, $display=null, $image=null, $position=null)
    {
        return Mage::getModel('ajaxnavigation/item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count)
            ->setActive($active)
            ->setDisplay($display)
            ->setImage($image)
            ->setPosition($position);
    }
    
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $items[] = $this->_createItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
                $itemData['active'],
                $itemData['display'],
                $itemData['image'],
                $itemData['position']
            );
        }
        $this->_items = $items;
        return $this;
    }

    /**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('ajaxnavigation/attribute');
        }
        return $this->_resource;
    }

    /**
     * Get option text from frontend model by option id
     *
     * @param   int $optionId
     * @return  unknown
     */
    protected function _getOptionText($optionId)
    {
        return $this->getAttributeModel()->getFrontend()->getOption($optionId);
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Varien_Object $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Attribute
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = $request->getParam($this->_requestVar);
        $this->setData('request', $filter);
        
        $expFilter = explode('-', $filter);
        if (!$filter) {
            return $this;
        }
        $this->setData('current_filter' ,$expFilter);
        $this->_getResource()->applyFilterToCollection($this, $expFilter);
        
        foreach ($expFilter as $filters) {
            $text = $this->_getOptionText($filters);
            
            $this->getLayer()->getState()->addFilter($this->_createItem($text, $filters));
//            $this->_items = array();
        }
        
        return $this;
    }

    /**
     * Check whether specified attribute can be used in LN
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @return bool
     */
    protected function _getIsFilterableAttribute($attribute)
    {
        return $attribute->getIsFilterable();
    }

    /**
     * Get data array for building attribute filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $display = 1;
        $sorter = null;
        $position = null;
        
        $attribute = $this->getAttributeModel();
        
        $this->_requestVar = $attribute->getAttributeCode();
        
        $currnetFilter = $this->getData('current_filter');

        $key = $this->getLayer()->getStateKey().'_'.$this->_requestVar;
        $data = null;

        if ($data === null) {
            $options = $attribute->getFrontend()->getSelectOptions();

            $optionsCount = $this->_getResource()->getCount($this);
            $data = array();

            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])) {
                    if ($this->_getIsFilterableAttribute($attribute) == self::OPTIONS_ONLY_WITH_RESULTS) {
                        if (null !== $currnetFilter) {
                            foreach ($currnetFilter as $value) {
                                if ($option['value'] == $value) {
                                    $data[] = array(
                                        'label'   => $option['label'],
                                        'value'   => $option['value'],
                                        'count'   => 0,
                                        'active'  => 1,
                                        'display' => $display,
                                        'image'   => $image,
                                        'position' => $position
                                    );
                                }
                            }
                        }
                        if (!empty($optionsCount[$option['value']])) {
                                $data[] = array(
                                    'label'   => $option['label'],
                                    'value'   => $option['value'],
                                    'count'   => $optionsCount[$option['value']],
                                    'active'  => 0,
                                    'display' => $display,
                                    'image'   => $image,
                                    'position' => 0
                                );
                            
                        }
                    }
                    else {
                            $data[] = array(
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                                'active'  => 0,
                                'display' => $display,
                                'image'   =>$image,
                                'position' => 0
                            );
                        
                        
                    }
                }
            }
            
            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG.':'.$attribute->getId()
            );

            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        // Zend_Debug::dump($data);
        return $data;
    }
    
    public function getUrlValue($value, $var_name)
    {
        $result = array();
        $oldQuery = array();
        $query = array();
        
        if (Mage::registry('query_request')) {
            $oldQuery = Mage::registry('query_request');
        } elseif (Mage::registry('root_request')) {
            $oldQuery = Mage::registry('root_request');
        }
        
        if (array_key_exists($var_name, $oldQuery)) {
            $oldQuery[$var_name] .= '-' . $value;
        } else {
            $query = array(
                $var_name => $value,
                Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls,
            );
        }
        $result = array_merge($query, $oldQuery);

        return $result;        
    }
    
    public function getResetValue($currentValue=null, $varName=null)
    {
        $result = array();
        $oldQuery = array();
        
        if (Mage::registry('query_request')) {
            $oldQuery = Mage::registry('query_request');
        } elseif (Mage::registry('root_request')) {
            $oldQuery = Mage::registry('root_request');
        }

        if (array_key_exists($varName, $oldQuery)) {
            if (count(explode('-', $oldQuery[$varName])) > 1) {
                $oldQuery[$varName] = str_replace(array('-' . $currentValue, $currentValue . '-'), array('', ''), $oldQuery[$varName]);
            } else {
                $oldQuery[$varName] = null;
            }
        }
        $result = $oldQuery;
        
        return $result;
    }
    
}
