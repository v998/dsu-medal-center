<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
$sql = <<<EOT
DROP TABLE IF EXISTS `pre_dsu_medaltype`;

DROP TABLE IF EXISTS `pre_dsu_medalfield`;

EOT;
runquery($sql);
$finish = TRUE;
require DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/stat.inc.php';