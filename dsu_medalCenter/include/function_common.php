<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
!defined('IN_DISCUZ') && exit('Access Denied');

/**
 * �����û�UID��ȡ�û�ѫ����Ϣ
 * @param <int> $uid �û�UID
 * @param <bool> $expiration ���ؽ�����Ƿ����ѫ�µĹ���ʱ�䣬Ĭ��Ϊfalse
 * @return <array>�û���ѫ����Ϣ����$expirationΪTRUEʱΪarray('ѫ��1'=>'����ʱ��1')������Ϊarray(ѫ��1��ѫ��2...)��
 */
function getMedalByUid($uid = '', $expiration = false){
	global $_G;
	static $usermedalArr = array();
	$uid = empty($uid) ? $_G['uid'] : $uid;
	if(empty($usermedalArr[$uid])) {
		$usermedal = DB::result_first("SELECT medals FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'");
		$medalArr = $usermedal ? explode("\t", $usermedal) : array();
		$medalArr2 = array();
		foreach($medalArr as $medal){
			list($_medalid, $_expiration) = explode('|', $medal);
			$medalArr2[$_medalid] = intval($_expiration);
		}
		$usermedalArr[$uid] = $medalArr2;
	}
	return $expiration ? $usermedalArr[$uid] : array_keys($usermedalArr[$uid]);
}

/**
 * ���ڱ���һЩ������
 * @param <string> $name Ҫ����������������
 * @param <mixed> $data Ҫ������������ֵ
 * @param <string> $script ���б����������Դ��������Ϊ�˷�ֹ������ͬ���������Ƶ�ʱ����ɵĳ�ͻ��Ĭ��Ϊ�ա�
 */
function dsuMedal_saveOption($name, $data, $script = ''){
	$name = 'dsuMedal'.substr(md5($script.$name), 8, 16);
	save_syscache($name, $data);
}

/**
 * ��ȡ�����������
 * @param <string> $name ����������������
 * @param <string> $script ������Դ��������Ϊ�˷�ֹ������ͬ���������Ƶ�ʱ����ɵĳ�ͻ��Ĭ��Ϊ�ա�
 * @return <mixed> ������������ֵ
 */
function dsuMedal_getOption($name, $script = ''){
	$name = 'dsuMedal'.substr(md5($script.$name), 8, 16);
	$option = DB::fetch_first("SELECT * FROM ".DB::table('common_syscache')." WHERE cname='$name'");
	if(empty($option)){
		return NULL;
	}else{
		if($option['ctype']) {
			$option['data'] = unserialize($option['data']);
		}
		return $option['data'];
	}
}

/**
 * ��ȡ��������չ�ű���
 * @return <array>��������չ�ű��࣬��ʽΪarray(����=>����)
 */
function getMedalExtendClass(){
	global $_G;
	static $classes = array();
	if(empty($classes)){
		$modlist = dsuMedal_getOption('modlist');

		$modlist = array_keys($modlist);
		foreach($modlist as $classname){
	   		include_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/script/'.$classname.'.php';
	   		if(class_exists($classname)){
	   			$newclass = new $classname;
	   			$classes[$classname] = $newclass;
	   		}
		}
	}
	return $classes;
}

/**
 * ��ȡѫ�����Ĳ����������н���
 * @return <array>�������ѫ�����Ĳ��������
 */
function dsuMedal_phraseConfig(){
	global $_G;
	loadcache('plugin');
	$cvars = &$_G['cache']['plugin']['dsu_medalCenter'];
	$cvars['showMedalLimit'] = (array) unserialize($cvars['showMedalLimit']);
	return $cvars;
}

/**
 * ���ָ������Ƿ��Ѿ���װ
 * @param <string> �����identifier
 * @return <bool>ָ������Ƿ��Ѿ���װ
 */
function dsuMedal_pluginExists($identifier){
	$identifier = addslashes($identifier);
	$plugin = DB::fetch_first("SELECT * FROM ".DB::table('common_plugin')." WHERE identifier = '$identifier'");
	return !empty($plugin);
}
?>