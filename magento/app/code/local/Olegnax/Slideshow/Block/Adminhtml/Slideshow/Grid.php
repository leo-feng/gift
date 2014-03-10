<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Block_Adminhtml_Slideshow_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('slideshowGrid');
      $this->setDefaultSort('slide_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('slideshow/slideshow')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('slide_id', array(
          'header'    => Mage::helper('slideshow')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'slide_id',
      ));

      if (!Mage::app()->isSingleStoreMode()) {
          $this->addColumn('store_id', array(
              'header'        => Mage::helper('slideshow')->__('Store View'),
              'index'         => 'store_id',
              'type'          => 'store',
              'store_all'     => true,
              'store_view'    => true,
              'sortable'      => false,
              'filter_condition_callback'
              => array($this, '_filterStoreCondition'),
          ));
      }

      $this->addColumn('title', array(
          'header'    => Mage::helper('slideshow')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('link', array(
          'header'    => Mage::helper('slideshow')->__('Link'),
          'align'     =>'left',
          'index'     => 'link',
      ));

	  $this->addColumn('image', array(
          'header'    => Mage::helper('slideshow')->__('Image'),
          'align'     =>'left',
          'index'     => 'image',
		  'renderer' => 'slideshow/adminhtml_slideshow_grid_renderer_image'
      ));

	  $this->addColumn('status', array(
          'header'    => Mage::helper('slideshow')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));

      $this->addColumn('sort_order', array(
          'header'    => Mage::helper('slideshow')->__('Sort Order'),
          'align'     =>'left',
          'index'     => 'sort_order',
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('slideshow')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('slideshow')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

      return parent::_prepareColumns();
  }

	protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('slide_id');
        $this->getMassactionBlock()->setFormFieldName('slideshow');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('slideshow')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('slideshow')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('slideshow/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('slideshow')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('slideshow')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}