<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
?>
<?php

class Magazento_Tabs_Block_Admin_Tab_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('TabsGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('tabs/tab')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $baseUrl = $this->getUrl();
        $this->addColumn('tab_id', array(
            'header' => Mage::helper('tabs')->__('ID'),
            'align' => 'left',
            'width' => '30px',
            'index' => 'tab_id',
        ));
        $this->addColumn('title', array(
            'header' => Mage::helper('tabs')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));
        $this->addColumn('url', array(
            'header' => Mage::helper('tabs')->__('Url'),
            'align' => 'left',
            'index' => 'url',
        ));	
        $this->addColumn('position', array(
            'header' => Mage::helper('tabs')->__('Pos'),
            'align' => 'left',
            'index' => 'position',
            'width' => '20px',
            'type'  => 'options',
            'options' => Mage::helper('tabs')->numberArray(20,Mage::helper('tabs')->__('')),
        ));
		
        $this->addColumn('type', array(
            'header' => Mage::helper('tabs')->__('Type (Tab/Link)'),
            'index' => 'type',
            'type' => 'options',            
	        'options' => array(
	                0 => Mage::helper('tabs')->__('Link'),
	                1 => Mage::helper('tabs')->__('Tab'),
	            ),            
        ));
		
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('tabs')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

       $this->addColumn('align_tab', array(
            'header' => Mage::helper('tabs')->__('Align'),
            'align' => 'left',
            'index' => 'align_tab',
            'width' => '30px',
            'type'  => 'options',
            'options' => array(
                'left' => Mage::helper('tabs')->__('Left'),
                'right' => Mage::helper('tabs')->__('Right'),
            )
        ));


        $this->addColumn('is_active', array(
            'header' => Mage::helper('tabs')->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('tabs')->__('Disabled'),
                1 => Mage::helper('tabs')->__('Enabled'),
            ),
        ));

        $this->addColumn('from_time', array(
            'header' => Mage::helper('tabs')->__('From Time'),
            'index' => 'from_time',
            'type' => 'datetime',
        ));

        $this->addColumn('to_time', array(
            'header' => Mage::helper('tabs')->__('To Time'),
            'index' => 'to_time',
            'type' => 'datetime',
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('tabs')->__('Action'),
                    'index' => 'tab_id',
                    'sortable' => false,
                    'filter' => false,
                    'no_link' => true,
                    'width' => '100px',
                    'renderer' => 'tabs/admin_tab_grid_renderer_action'
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('tabs')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tabs')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('tab_id');
        $this->getMassactionBlock()->setFormFieldName('massaction');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('tabs')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tabs')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('tabs')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('tabs')->__('Status'),
                    'values' => array(
                        0 => Mage::helper('tabs')->__('Disabled'),
                        1 => Mage::helper('tabs')->__('Enabled'),
                    ),
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('tab_id' => $row->getId()));
    }

}
