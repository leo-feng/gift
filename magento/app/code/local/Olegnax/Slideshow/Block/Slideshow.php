<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Block_Slideshow extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getSlideshow()
    {
        if (!$this->hasData('slideshow')) {
            $this->setData('slideshow', Mage::registry('slideshow'));
        }
        return $this->getData('slideshow');
    }

    public function getSlides()
    {
        $slides  = Mage::getModel('slideshow/slideshow')->getCollection()
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToSelect('*')
            ->addFieldToFilter('status', 1)
            ->setOrder('sort_order', 'asc');
        return $slides;
    }

}