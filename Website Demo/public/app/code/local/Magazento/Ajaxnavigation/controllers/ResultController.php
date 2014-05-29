<?php
$controller = BP . DS . 'app' . DS . 'code' . DS . 'core' . DS . 'Mage' . DS . 'CatalogSearch' . DS . 'controllers' . DS . 'ResultController.php';
require_once $controller;
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_CatalogSearch
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog Search Controller
 */

class Magazento_Ajaxnavigation_ResultController extends Mage_CatalogSearch_ResultController
{
    /**
     * Display search result
     */
    public function indexAction()
    {
        $query = array();
        $query = $this->getRequest()->getQuery();
        
        Mage::register('query_request', $query);
        
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return parent::indexAction();
        }
        $this->loadLayout();
        $layout = $this->getLayout();
        $blocks = array(
            'list'     => 'search.result',
            'filter'   => 'ajaxnavigation.catalogsearch.leftnav',
        );
        $result = array();
        foreach ($blocks as $key => $block) {
            $result[$key] = $layout->getBlock($block)->toHtml();
        }
        
        
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
        
        $newQuery = Mage::helper('catalogsearch')->getQuery();
        $result['categoryName'] = '';
        
        $this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
}
