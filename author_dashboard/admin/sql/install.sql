CREATE TABLE IF NOT EXISTS `#__cf-user_info` (
  `user_id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `patronym` varchar(255) DEFAULT NULL,
  `organisation` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cf_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `article_name` text NOT NULL,
  `article_authors` text NOT NULL,
  `key_words` text NOT NULL,
  `anotation` text NOT NULL,
  `status` int(5) NOT NULL DEFAULT '0',
  `etc` text,
  `path` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;