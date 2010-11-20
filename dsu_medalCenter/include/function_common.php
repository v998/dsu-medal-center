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
?>