<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Block_Admin_Slide_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('HeadersliderGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('headerslider/slide')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }


    public function addStoreFilter($store, $withAdmin = true) {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        $this->getSelect()->join(
            array('slide_store' => $this->getTable('instagram/slide_store')),
            'main_table.slide_id = slide_store.slide_id',
            array()
        )
            ->where('slide_store.store_id in (?)', ($withAdmin ? array(0, $store) : $store));

        return $this;
    }

    protected function _prepareColumns() {

        $baseUrl = $this->getUrl();
        $this->addColumn('slide_id', array(
            'header' => Mage::helper('headerslider')->__('ID'),
            'align' => 'left',
            'width' => '30px',
            'index' => 'slide_id',
        ));
        $this->addColumn('position', array(
            'header' => Mage::helper('headerslider')->__('Position'),
            'align' => 'left',
            'index' => 'position',
            'width' => '30px',
        ));
        
        $this->addColumn('media', array(
            'header' => Mage::helper('headerslider')->__('Image'),
            'align' => 'left',
            'index' => 'media',
            'width' => '200px',            
            'renderer'  => 'headerslider/admin_slide_grid_renderer_media'            
        ));

        $this->addColumn('is_active', array(
            'header' => Mage::helper('headerslider')->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('headerslider')->__('Disabled'),
                1 => Mage::helper('headerslider')->__('Enabled'),
            ),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('headerslider')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'width'         => '120px',
                'store_view'    => true,
                'sortable'      => true,
                'filter_condition_callback'  => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('from_time', array(
            'header' => Mage::helper('headerslider')->__('From Time'),
            'index' => 'from_time',
            'type' => 'datetime',
        ));

        $this->addColumn('to_time', array(
            'header' => Mage::helper('headerslider')->__('To Time'),
            'index' => 'to_time',
            'type' => 'datetime',
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('headerslider')->__('Action'),
                    'index' => 'slide_id',
                    'sortable' => false,
                    'filter' => false,
                    'no_link' => true,
                    'width' => '200px',
                    'renderer' => 'headerslider/admin_slide_grid_renderer_action'
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('headerslider')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('headerslider')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterCategoryCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addCategoryFilter($value);
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('slide_id');
        $this->getMassactionBlock()->setFormFieldName('massaction');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('headerslider')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('headerslider')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('headerslider')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('headerslider')->__('Status'),
                    'values' => array(
                        0 => Mage::helper('headerslider')->__('Disabled'),
                        1 => Mage::helper('headerslider')->__('Enabled'),
                    ),
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('slide_id' => $row->getId()));
    }

}
