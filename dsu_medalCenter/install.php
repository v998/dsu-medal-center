<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
$sql = <<<EOT
DROP TABLE IF EXISTS `pre_dsu_medaltype`;
CREATE TABLE `pre_dsu_medaltype` (
  `typeid` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `displayorder` smallint(3) unsigned zerofill NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS `pre_dsu_medalfield`;
CREATE TABLE `pre_dsu_medalfield` (
  `medalid` smallint(6) unsigned NOT NULL,
  `typeid` smallint(3) unsigned NOT NULL,
  `gettype` smallint(1) unsigned NOT NULL DEFAULT '1',
  `script` text,
  `setting` text,
  PRIMARY KEY (`medalid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM;
EOT;
runquery($sql);
$finish = TRUE;

require DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/stat.inc.php';
