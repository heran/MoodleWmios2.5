CREATE TABLE `mdl_multimedia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `intro` longtext,
  `introformat` smallint(6) DEFAULT NULL,
  `content` longtext,
  `contentformat` smallint(6) DEFAULT NULL,
  `display` smallint(6) DEFAULT NULL,
  `displayoptions` longtext,
  `type` bigint(20) DEFAULT NULL,
  `completion_max` float DEFAULT NULL,
  `completionenabled` tinyint(4) NOT NULL DEFAULT '0',
  `timemodified` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE `mdl_multimedia_completion` (
  `mid` bigint(20) NOT NULL DEFAULT '0',
  `userid` bigint(20) NOT NULL DEFAULT '0',
  `completion_now` float DEFAULT NULL,
  PRIMARY KEY (`mid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;