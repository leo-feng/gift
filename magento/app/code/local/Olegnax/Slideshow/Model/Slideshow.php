<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Model_Slideshow extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow/slideshow');
    }
}