<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');

loadcache('plugin');
$modlist = &$_G['cache']['plugin']['dsu_medalCenter']['modlist'];
$modlist = is_array($modlist) ? $modlist : (array)unserialize($modlist);

if(in_array($_G['gp_pdo'], array('install', 'upgrade', 'uninstall'))){ //�ű�����
	$classname = $_G['gp_classname'];
	if(!preg_match("/^[a-zA-Z0-9_]+$/", $classname) || !file_exists(DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php')){
		cpmsg("BAD INPUT", '', 'error');
	}else{
		include DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php';
		if(class_exists($classname)){
			$newclass = new $classname;
		}else{
			cpmsg('��չ�ļ��Ѿ��𻵣�', '', 'error');
		}
	}
	switch($_G['gp_pdo']){
		case 'install':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'install')) $newclass->install();
			$msg = 'ָ����չ��װ�ɹ���';
			break;
		case 'uninstall':
			unset($modlist[$classname]);
			if(method_exists($newclass, 'uninstall')) $newclass->uninstall();
			$msg = 'ָ����չж�سɹ���';
			break;
		case 'upgrade':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'upgrade')) $newclass->upgrade();
			$msg = 'ָ����չ�����ɹ���';
			break;
	}
	$modlist = serialize($modlist);
	$pluginid = DB::result_first("SELECT pluginid FROM ".DB::table('common_plugin')." WHERE identifier='dsu_medalCenter'");
	DB::query("UPDATE ".DB::table('common_pluginvar')." SET value='$modlist' WHERE pluginid='$pluginid' and variable='modlist'");
	updatecache('plugin');
	cpmsg($msg, 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend', 'succeed');
}else{
	showtips('<li>��װ�µ���չ���轫��չ�ű������ϴ��� source/plugin/dsu_medalCenter/include/script/ Ŀ¼��Ȼ�󼴿��������б��а�װ��ʹ����</li>');
	showtableheader('');
	showsubtitle(array('����', '�汾��', '��Ȩ��Ϣ', ''));
	$dir = dir(DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/');
	while (false !== ($entry = $dir->read())) {
		if(substr($entry, 0, 7) != 'script_' || substr($entry, -4) != '.php') continue;
		include DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$entry;
		$classname = substr($entry, 0, -4);
		if(class_exists($classname)){
			$newclass = new $classname;
			if(empty($newclass->name)) continue;
			$adminaction = $namemsg = $versionmsg = '';
			$namemsg = $newclass->name;
			$versionmsg = $newclass->version;
			if(!empty($newclass->introduction)){ //��չ����
				$namemsg = '<span title="'.$newclass->introduction.'">'.$namemsg.'</span>';
			}else{
				$namemsg = '<span>'.$namemsg.'</span>';
			}
			if(isset($modlist[$classname])){ //����Ƿ��Ѿ���װ
				if($modlist[$classname] < $newclass->version){ //�Ƿ���Ҫ����
					$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=upgrade&classname=$classname\" class=\"act\">����</a>" ;
					$versionmsg .= '(��ǰ��װ�汾��'.$modlist[$classname].')';
				}
				$namemsg = "<strong>$newclass->name</strong>";
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=uninstall&classname=$classname\" class=\"act\">ж��</a>";
			}else{
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=install&classname=$classname\" class=\"act\">��װ</a>";
			}
			showtablerow('', array('class="td25"', 'class="td25"', 'class="td25"', 'class="td25"'), array(
					$namemsg,
					$versionmsg,
					$newclass->copyright,
					$adminaction
				));
			
		}
	}
	$dir->close();
	showtablefooter();
}
?>