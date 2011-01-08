<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
//��Ҫ���Ƶ��ļ�/�ļ���(Դ��ַ���ս���Ŀ��λ�ý���һ�����ļ���)
$fileList = array(
	array('', 'data/plugin/dsu_medalCenter'),
	array('source/plugin/dsu_medalCenter/include/install/files/cache_dsuMedalCenter.php', 'source/function/cache/cache_dsuMedalCenter.php'),
);
//���ݿ��������
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
	1 => array('����ļ�����', $step == 1),
	2 => array('���ݿ�����', $step == 2),
	3 => array('����Ĭ������', $step == 3),
);
if($step >= 1 && $step <=2) showsubmenusteps('��DSU��ѫ�����İ�װ����', $stepArr);
$nextstep = max(intval($_G['gp_nextstep']), $step);
if($nextstep == $step && !empty($stepArr[$step])){
	$nextstep = $nextstep + 1;
	cpmsg('����ִ���У����Ժ󡭡�',"action=plugins&operation=plugininstall&dir=dsu_medalCenter&step=$step&nextstep=$nextstep", 'loading');
}else{
	$nextstep = $step + 1;
}

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
	cpmsg('����Ĭ��������ɣ�������һ��������','action=plugins&operation=plugininstall&dir=dsu_medalCenter&step='.$nextstep, 'succeed');
}else{
	require DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/install/stat.inc.php';
	$finish = TRUE;
}

