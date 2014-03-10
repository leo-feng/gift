<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Luxurysettings_Model_Settings extends Mage_Core_Model_Abstract
{
    /**
     * theme settings
     *
     * @var Varien_Simplexml_Config
     */
    protected $settings;

    /**
     * load theme xml
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings = new Varien_Simplexml_Config();
        $this->settings->loadFile(Mage::getBaseDir().'/app/code/local/Olegnax/Luxurysettings/etc/luxury.xml');
        if ( !$this->settings ) {
            throw new Exception('Can not read theme config file '.Mage::getBaseDir().'/app/code/local/Olegnax/Luxurysettings/etc/luxury.xml');
        }
    }

    /**
     * create/update cms pages & blocks
     */
    public function setupCms()
    {
        foreach ( $this->settings->getNode('cms/pages')->children() as $item ) {
            $this->_processCms($item, 'cms/page');
        }

	    foreach ( $this->settings->getNode('cms/blocks')->children() as $item ) {
            $this->_processCms($item, 'cms/block');
        }

    }

    /**
     * create/update cms page/static block
     *
     * @param $page SimpleXMLElement
     */
    protected function _processCms($item, $model)
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

}
