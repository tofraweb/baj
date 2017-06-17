CREATE TABLE IF NOT EXISTS `#__poweradmin_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `object_key` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  `component` varchar(255) NOT NULL,
  `list_page` varchar(255) NOT NULL,
  `list_page_params` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `form` text NOT NULL,
  `form_hash` varchar(32) NOT NULL,
  `params` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `css` varchar(100) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `visited` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;