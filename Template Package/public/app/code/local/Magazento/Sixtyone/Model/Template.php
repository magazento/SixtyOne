<?php
/**
 *
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Sixtyone_Model_Template extends Mage_Core_Model_Abstract
{
    protected $settings;
    private $_file = '/app/code/local/Magazento/Sixtyone/import/data.xml';

    public function __construct()
    {
        parent::__construct();
        $this->settings = new Varien_Simplexml_Config();
        $this->settings->loadFile(Mage::getBaseDir().$this->_file);
        if ( !$this->settings ) {
            throw new Exception('Can not read theme config file '.Mage::getBaseDir().$this->_file);
        }
    }

    public function setupPages()
    {
        foreach ( $this->settings->getNode('cms/pages')->children() as $item ) {
            $this->_processEntity($item, 'cms/page');
        }

    }

    public function setupBlocks()
    {

        foreach ( $this->settings->getNode('cms/blocks')->children() as $item ) {
            $this->_processEntity($item, 'cms/block');
        }

    }

    protected function _processEntity($item, $model)
    {
        $cmsPage = array();
        foreach ( $item as $p ) {
            $cmsPage[$p->getName()] = (string)$p;
            if ( $p->getName() == 'stores' ) {
                $cmsPage[$p->getName()] = array();
                foreach ( $p as $store ) {
                    $cmsPage[$p->getName()][] = (string)$store;
                }
            }
        }

        $orig_page = Mage::getModel($model)->getCollection()
            ->addFieldToFilter('identifier', array( 'eq' => $cmsPage['identifier'] ))
            ->load();
        if (count($orig_page)) {
            foreach ($orig_page as $_page) {
                $_page->delete();
            }
        }

        Mage::getModel($model)->setData($cmsPage)->save();

    }

    public function setDefaultSettings($scope, $store) {

        $defaults = new Varien_Simplexml_Config();
        $defaults->loadFile(Mage::getBaseDir().'/app/code/local/Magazento/Sixtyone/etc/config.xml');
        $this->_restoreSettings($defaults->getNode('default/general'), 'general',$scope, $store);
    }


    private function _restoreSettings($items, $path,$scope, $store)
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->_restoreSettings($item->children(), $path.'/'.$item->getName(),$scope, $store);
            } else {
                if ($this->_clear) {
                    Mage::getConfig()->deleteConfig($path.'/'.$item->getName(), $scope, $store);
                }
                Mage::getConfig()->saveConfig($path.'/'.$item->getName(), (string)$item, $scope, $store);
            }
        }
    }

}