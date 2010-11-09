<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id: function_common.php 3 2010-10-31 07:58:06Z chuzhaowei@gmail.com $
*/

/**
 * 根据用户UID获取用户勋章信息
 * @param <int> $uid 用户UID
 * @return <array>用户的勋章信息
 */
function getMedalExtend(){
	
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