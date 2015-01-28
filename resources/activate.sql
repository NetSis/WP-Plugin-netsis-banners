CREATE TABLE IF NOT EXISTS `wp_netsis_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `link` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_UNIQUE` (`file`)
)