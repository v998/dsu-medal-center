<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');

require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/function_common.php';

$modlist = dsuMedal_getOption('modlist');
$sysmod = array('script_market');

if(in_array($_G['gp_pdo'], array('install', 'upgrade', 'uninstall'))){ //�ű�����
	$classname = $_G['gp_classname'];
	if(!preg_match("/^[a-zA-Z0-9_]+$/", $classname) || !file_exists(DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php')){
		cpmsg("BAD INPUT", '', 'error');
	//}else if($_G['gp_pdo'] == 'uninstall' && in_array($classname, $sysmod)){
	//	cpmsg('ϵͳģ�飬��ֹ������', '', 'error');
	}else{
		@include DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php';
		if(class_exists($classname)){
			$newclass = new $classname;
		}else{
			cpmsg('��չ�ļ��Ѿ��𻵣�', '', 'error');
		}
	}
	$return = TRUE;
	switch($_G['gp_pdo']){
		case 'install':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'install')) $return = $newclass->install();
			$msg = 'ָ����չ��װ�ɹ���';
			break;
		case 'uninstall':
			unset($modlist[$classname]);
			if(method_exists($newclass, 'uninstall')) $return = $newclass->uninstall();
			$msg = 'ָ����չж�سɹ���';
			break;
		case 'upgrade':
			$modlist[$classname] = $newclass->version;
			if(method_exists($newclass, 'upgrade')) $return = $newclass->upgrade();
			$msg = 'ָ����չ�����ɹ���';
			break;
	}
	if(is_array($return)) list($return, $msg2) = $return;
	$msg = $msg2 ? $msg2 : ($return === FALSE ? '����ʧ�ܣ�' : $msg);
	if($return === FALSE) {
		cpmsg($msg, 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend', 'error');
	}else{
		dsuMedal_saveOption('modlist', $modlist);
		cpmsg($msg, 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend', 'succeed');
	}
}else{
	showtips('<li>��װ�µ���չ���轫��չ�ű������ϴ��� source/plugin/dsu_medalCenter/include/script/ Ŀ¼��Ȼ�󼴿��������б��а�װ��ʹ����</li><li>���ֹ���ģ��Ϊѫ���������б�Ҫģ�飬�޷��Ƴ�</li>');
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
			$introduction = empty($newclass->introduction) ? $newclass->name : $newclass->introduction;
			if(isset($modlist[$classname])){ //����Ƿ��Ѿ���װ
				$namemsg = "<strong>$newclass->name</strong>";
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=uninstall&classname=$classname\" class=\"act\">ж��</a>";
				if($modlist[$classname] < $newclass->version){ //�Ƿ���Ҫ����
					$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=upgrade&classname=$classname\" class=\"act\">����</a>" ;
					$versionmsg .= '(��ǰ��װ�汾��'.$modlist[$classname].')';
				}
			}else{
				$adminaction .= "<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_extend&pdo=install&classname=$classname\" class=\"act\">��װ</a>";
			}
			$namemsg = '<span title="'.$introduction.'">'.$namemsg.'</span>';
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