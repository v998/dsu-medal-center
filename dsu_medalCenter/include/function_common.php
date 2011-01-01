<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

/**
 * 根据开启的扩展脚本类
 * @return <array>开启的扩展脚本类，格式为array(类名=>对象)
 */
function getMedalExtendClass(){
	global $_G;
	static $classes = array();
	if(empty($classes)){
		$_G['cache']['plugin'] && loadcache('plugin');
		$modlist = &$_G['cache']['plugin']['dsu_medalCenter']['modlist'];
		$modlist = is_array($modlist) ? $modlist : (array)unserialize($modlist);

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
 * 根据用户UID获取用户勋章信息
 * @param <int> $uid 用户UID
 * @return <array>用户的勋章信息
 */
function getMedalByUid($uid = ''){
	global $_G;
	static $usermedalArr = array();
	$uid = empty($uid) ? $_G['uid'] : $uid;
	if(empty($usermedalArr[$uid])) {
		$usermedal = DB::result_first("SELECT medals FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'");
		$usermedalArr[$uid] = $usermedal ? explode("\t", $usermedal) : array();
	}
	return $usermedalArr[$uid];
}

/**
 * 用于保存一些设置项
 * @param <string> $name 要保存的设置项的名称
 * @param <mixed> $data 要保存的设置项的值
 * @param <string> $script 进行保存操作的来源，此项是为了防止在有相同保存项名称的时候造成的冲突，默认为空。
 */
function dsuMedal_saveOption($name, $data, $script = ''){
	$name = 'dsuMedal'.substr(md5($script.$name), 8, 16);
	save_syscache($name, $data);
}

/**
 * 获取保存的设置项
 * @param <string> $name 保存的设置项的名称
 * @param <string> $script 操作来源，此项是为了防止在有相同保存项名称的时候造成的冲突，默认为空。
 * @return <mixed> 保存的设置项的值
 */
function dsuMedal_getOption($name, $script = ''){
	$name = 'dsuMedal'.substr(md5($script.$name), 8, 16);
	$option = DB::fetch_first("SELECT /*!40001 SQL_CACHE */ * FROM ".DB::table('common_syscache')." WHERE cname='$name'");
	if(empty($option)){
		return NULL;
	}else{
		if($option['ctype']) {
			$option['data'] = unserialize($option['data']);
		}
		return $option['data'];
	}
}
?>