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

if(in_array($_G['gp_pdo'], array('install', 'upgrade', 'uninstall'))){ //脚本操作
	$classname = $_G['gp_classname'];
	if(!preg_match("/^[a-zA-Z0-9_]+$/", $classname) || !file_exists(DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php')){
		cpmsg("BAD INPUT", '', 'error');
	}else{
		include DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php';
		if(class_exists($classname)){
			$newclass = new $classname;
		}else{
			cpmsg('扩展文件已经损坏！', '', 'error');
		}
	}
	switch($_G['gp_pdo']){
		case 'install':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'install')) $newclass->install();
			$msg = '指定扩展安装成功！';
			break;
		case 'uninstall':
			unset($modlist[$classname]);
			if(method_exists($newclass, 'uninstall')) $newclass->uninstall();
			$msg = '指定扩展卸载成功！';
			break;
		case 'upgrade':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'upgrade')) $newclass->upgrade();
			$msg = '指定扩展升级成功！';
			break;
	}
	$modlist = serialize($modlist);
	$pluginid = DB::result_first("SELECT pluginid FROM ".DB::table('common_plugin')." WHERE identifier='dsu_medalCenter'");
	DB::query("UPDATE ".DB::table('common_pluginvar')." SET value='$modlist' WHERE pluginid='$pluginid' and variable='modlist'");
	updatecache('plugin');
	cpmsg($msg, 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend', 'succeed');
}else{
	showtips('<li>安装新的扩展，需将扩展脚本程序上传到 source/plugin/dsu_medalCenter/include/script/ 目录，然后即可在以下列表中安装并使用了</li>');
	showtableheader('');
	showsubtitle(array('名称', '版本号', '版权信息', ''));
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
			if(!empty($newclass->introduction)){ //扩展介绍
				$namemsg = '<span title="'.$newclass->introduction.'">'.$namemsg.'</span>';
			}else{
				$namemsg = '<span>'.$namemsg.'</span>';
			}
			if(isset($modlist[$classname])){ //检查是否已经安装
				if($modlist[$classname] < $newclass->version){ //是否需要升级
					$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=upgrade&classname=$classname\" class=\"act\">升级</a>" ;
					$versionmsg .= '(当前安装版本：'.$modlist[$classname].')';
				}
				$namemsg = "<strong>$newclass->name</strong>";
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=uninstall&classname=$classname\" class=\"act\">卸载</a>";
			}else{
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=install&classname=$classname\" class=\"act\">安装</a>";
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