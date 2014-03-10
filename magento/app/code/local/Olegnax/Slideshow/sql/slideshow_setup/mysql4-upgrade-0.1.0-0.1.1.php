<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Drop 'store_id' column from 'slideshow/slideshow'
 */
$conn = $installer->getConnection();
$table = $installer->getTable('slideshow/slideshow');
$conn->dropColumn($table, 'store_id');

/**
 * Create table 'slideshow/slideshow_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('slideshow/slideshow_store'))
    ->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Slide ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('slideshow/slideshow_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('slideshow/slideshow_store', 'slide_id', 'slideshow/slideshow', 'slide_id'),
        'slide_id', $installer->getTable('slideshow/slideshow'), 'slide_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('slideshow/slideshow_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Slide To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Assign 'all store views' to existing slides
 */
$installer->run("INSERT INTO {$this->getTable('slideshow/slideshow_store')} (`slide_id`, `store_id`) SELECT `slide_id`, 0 FROM {$this->getTable('slideshow/slideshow')};");
$installer->endSetup();