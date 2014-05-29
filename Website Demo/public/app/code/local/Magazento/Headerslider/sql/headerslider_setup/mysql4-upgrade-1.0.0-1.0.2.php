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

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('magazento_headerslider_slide')} (
  `slide_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `position` smallint(6) DEFAULT NULL,
  `content` text,
  `image_filename` text NOT NULL,
  `from_time` datetime DEFAULT NULL,
  `to_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS `{$this->getTable('magazento_headerslider_slide_product')}` (
  `slide_id` int(11) NOT NULL,
  `product_id` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `{$this->getTable('magazento_headerslider_slide_store')}` (
  `slide_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


");

$installer->endSetup();


?>