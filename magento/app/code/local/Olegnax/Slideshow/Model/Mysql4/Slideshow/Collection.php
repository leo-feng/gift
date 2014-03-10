<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Model_Mysql4_Slideshow_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('slideshow/slideshow');
    }

    /**
     * Add Filter by store
     *
     * @param Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Olegnax_Slideshow_Model_Mysql4_Slideshow_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        $this->getSelect()->join(
            array('store_table' => $this->getTable('slideshow/slideshow_store')),
            'main_table.slide_id = store_table.slide_id',
            array()
        )
            ->where('store_table.store_id in (?)', ($withAdmin ? array(0, $store) : $store));

        return $this;
    }
}