<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

$filename = array(
	'data/plugin/dsu_medalCenter',
	'source/function/cache/cache_dsuMedalCenter.php',
);
$_sql = <<<EOT
DROP TABLE IF EXISTS `pre_dsu_medaltype`;
DROP TABLE IF EXISTS `pre_dsu_medalfield`;
EOT;

require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/FSO.class.php';
$step = max(intval($_G['gp_step']), 1);
$stepArr = array(
	1 => array('插件文件处理', $step == 1),
	2 => array('数据库升级', $step == 2),
);
if($step >= 1 && $step <=2) showsubmenusteps('【DSU】勋章中心卸载程序', $stepArr);
$nextstep = max(intval($_G['gp_nextstep']), $step);
if($nextstep == $step && !empty($stepArr[$step])){
	$nextstep = $nextstep + 1;
	cpmsg('操作执行中，请稍后……',"action=plugins&operation=pluginuninstall&dir=dsu_medalCenter&step=$step&nextstep=$nextstep", 'loading');
}else{
	$nextstep = $step + 1;
}

if($step == 1){
	foreach($fileList as $filename){
		@FSO::unlink($filename);
	}
	cpmsg($setpArr[$step][0].'完成！进入下一步操作。','action=plugins&operation=pluginuninstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}elseif($step == 2){
	runquery($_sql);
	cpmsg($setpArr[$step][0].'完成！进入下一步操作。','action=plugins&operation=pluginuninstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}else{
	require DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/stat.inc.php';
	$finish = TRUE;
}


