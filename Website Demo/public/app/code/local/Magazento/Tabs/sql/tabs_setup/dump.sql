-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 10, 2012 at 01:03 PM
-- Server version: 5.5.18
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `magento16`
--

-- --------------------------------------------------------

--
-- Table structure for table `magazento_tabs_tab`
--

CREATE TABLE IF NOT EXISTS `magazento_tabs_tab` (
  `tab_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `content` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `position` tinyint(10) NOT NULL DEFAULT '0',
  `align_tab` varchar(10) NOT NULL,
  `css_style` text NOT NULL,
  `css_class` varchar(100) NOT NULL,
  `css_style_content` text NOT NULL,
  `css_class_content` varchar(100) NOT NULL,
  `from_time` datetime DEFAULT NULL,
  `to_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tab_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `magazento_tabs_tab`
--

INSERT INTO `magazento_tabs_tab` (`tab_id`, `title`, `url`, `content`, `type`, `position`, `align_tab`, `css_style`, `css_class`, `css_style_content`, `css_class_content`, `from_time`, `to_time`, `is_active`) VALUES
(22, 'Link1', '', '<table border="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h2 class="menu-content-header">Header1</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header2</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header3</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header4</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n</tr>\r\n</tbody>\r\n</table>', 0, 1, 'left', '', '', '', '', '2012-01-06 13:01:44', NULL, 1),
(23, 'Tab2', '#', '<p>435 435433 53&nbsp;</p>', 1, 1, 'left', '', '', '', '', '2012-01-06 13:02:01', NULL, 1),
(24, 'Tab3', '#', '', 1, 1, 'right', '', '', '', '', '2012-01-07 10:14:13', NULL, 1),
(25, 'Navigation tab 4', 'sample-menu-item.html', '<p>Sample content remove if needed.</p>\r\n<table border="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h2 class="menu-content-header">Header1</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header2</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header3</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n<td>\r\n<h2 class="menu-content-header">Header4</h2>\r\nDonec consequat lacinia erat eu tempus. Mauris lacinia nunc vitae quam molestie viverra. Aliquam iaculis interdum est, at laoreet augue euismod in. Sed vel nulla vitae magna bibendum viverra. Vivamus sit amet orci ut est tempor ullamcorper eleifend nec sapien. Integer erat enim, aliquet nec bibendum at, feugiat a est. Nam laoreet, mauris in accumsan ultricies, libero quam egestas nulla, et pretium lectus risus vel nulla. Praesent dignissim velit at justo vestibulum vitae semper ante luctus. Proin porta tempus dignissim. Donec ut sapien est, non gravida elit. Fusce eu est at sapien tincidunt lobortis.</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, 5, 'left', '', '', '', '', '2012-01-08 12:40:27', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `magazento_tabs_tab_store`
--

CREATE TABLE IF NOT EXISTS `magazento_tabs_tab_store` (
  `tab_id` smallint(6) unsigned DEFAULT NULL,
  `store_id` smallint(6) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `magazento_tabs_tab_store`
--

INSERT INTO `magazento_tabs_tab_store` (`tab_id`, `store_id`) VALUES
(13, 0),
(14, 0),
(15, 0),
(16, 0),
(12, 0),
(11, 0),
(25, 3),
(24, 3),
(23, 1),
(22, 0);
