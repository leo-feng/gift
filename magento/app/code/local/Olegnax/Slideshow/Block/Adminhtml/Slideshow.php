<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Block_Adminhtml_Slideshow extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_slideshow';
		$this->_blockGroup = 'slideshow';
		$this->_headerText = Mage::helper('slideshow')->__('Item Manager');
		$this->_addButtonLabel = Mage::helper('slideshow')->__('Add Item');
		parent::__construct();
	}
}