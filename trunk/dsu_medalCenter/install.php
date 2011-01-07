<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
//需要复制的文件/文件夹(源地址留空将在目标位置建立一个空文件夹)
$fileList = array(
	array('', 'data/plugin/dsu_medalCenter'),
);
//数据库升级语句
$_sql = <<<EOT
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

require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/FSO.class.php';
$step = max(intval($_G['gp_step']), 1);
$stepArr = array(
	1 => array('插件文件处理', $step == 1),
	2 => array('数据库升级', $step == 2),
	//3 => array('载入默认设置', $step == 3),
);
if($step >= 1 && $step <=2) showsubmenusteps('【DSU】勋章中心安装程序', $stepArr);
$nextstep = max(intval($_G['gp_nextstep']), $step);
if($nextstep == $step && !empty($stepArr[$step])){
	$nextstep = $nextstep + 1;
	cpmsg('操作执行中，请稍后……',"action=plugins&operation=plugininstall&dir=dsu_medalCenter&step=$step&nextstep=$nextstep", 'loading');
}else{
	$nextstep = $step + 1;
}

if($step == 1){
	foreach($fileList as $filename){
		if($filename[0] == ''){
			dmkdir(DISCUZ_ROOT.'./'.$filename[1]);
		}else{
			copy(DISCUZ_ROOT.'./'.$filename[0], DISCUZ_ROOT.'./'.$filename[1]);
		}
	}
	cpmsg('文件处理完成！进入下一步操作。','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}elseif($step == 2){
	runquery($_sql);
	cpmsg('数据库升级完成！进入下一步操作。','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}elseif($step == 3){
	cpmsg($setpArr[$step][0].'完成！进入下一步操作。','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}else{
	require DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/stat.inc.php';
	$finish = TRUE;
}

