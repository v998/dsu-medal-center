<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id: function_common.php 3 2010-10-31 07:58:06Z chuzhaowei@gmail.com $
*/

/**
 * �����û�UID��ȡ�û�ѫ����Ϣ
 * @param <int> $uid �û�UID
 * @return <array>�û���ѫ����Ϣ
 */
function getMedalExtend(){
	
}

/**
 * �����û�UID��ȡ�û�ѫ����Ϣ
 * @param <int> $uid �û�UID
 * @return <array>�û���ѫ����Ϣ
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