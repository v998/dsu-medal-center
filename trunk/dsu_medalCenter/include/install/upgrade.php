<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

$fileList = array(
);

$_sql = <<<EOT
EOT;

if($step == 1){
	foreach($fileList as $filename){
		if($filename[0] == ''){
			@dmkdir(DISCUZ_ROOT.'./'.$filename[1]);
		}else{
			@copy(DISCUZ_ROOT.'./'.$filename[0], DISCUZ_ROOT.'./'.$filename[1]);
		}
	}
	cpmsg('�ļ�������ɣ�������һ��������','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}elseif($step == 2){
	runquery($_sql);
	cpmsg('���ݿ�������ɣ�������һ��������','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}elseif($step == 3){
	require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/function_common.php';
	$modlist = array('script_market' => '1.0', 'script_usergroup' => '1.0');
	dsuMedal_saveOption('modlist', $modlist);

	cpmsg($setpArr[$step][0].'��ɣ�������һ��������','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}