<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

$installer = $this;
$installer->startSetup();
$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('slideshow/slideshow')}`;
CREATE TABLE `{$this->getTable('slideshow/slideshow')}` (
  `slide_id` int(11) unsigned NOT NULL auto_increment,
  `store_id` tinyint(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `button_text` varchar(32) NOT NULL DEFAULT '',
  `content_position` varchar(16) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$this->getTable('slideshow/slideshow')}` (`slide_id`, `store_id`, `title`, `text`, `button_text`, `content_position`, `link`, `image`, `status`, `created_time`, `update_time`) VALUES (1, 0, 'simply<br/>elegant', 'Looking forward to lightening up your wardrobe for spring?<br/>This playful ready-to-wear line, is the perfect mix of vintage<br/> and contemporary style.', 'shop now', 'left', 'http://olegnax.com/products/magento/', 'olegnax/slideshow/slide1.jpg', 1, NOW(), NOW() );
INSERT INTO `{$this->getTable('slideshow/slideshow')}` (`slide_id`, `store_id`, `title`, `text`, `button_text`, `content_position`, `link`, `image`, `status`, `created_time`, `update_time`) VALUES (2, 0, 'slide title', '', 'check it',  'center', 'http://olegnax.com/products/magento/', 'olegnax/slideshow/slide2.jpg', 1, NOW(), NOW() );
INSERT INTO `{$this->getTable('slideshow/slideshow')}` (`slide_id`, `store_id`, `title`, `text`, `button_text`, `content_position`, `link`, `image`, `status`, `created_time`, `update_time`) VALUES (3, 0, '', '', '', 'right', 'http://olegnax.com/products/magento/', 'olegnax/slideshow/slide3.jpg', 1, NOW(), NOW() );

");

$installer->endSetup();