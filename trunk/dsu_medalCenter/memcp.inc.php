<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
!defined('IN_DISCUZ') && exit('Access Denied');

loadcache('plugin');
$cvars = &$_G['cache']['plugin']['dsu_medalCenter'];
$thisurl = 'plugin.php?id=dsu_medalCenter:memcp';
require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/function_common.php';

if(empty($_G['gp_action']) || $_G['gp_action'] == 'list'){
	$query = DB::query("SELECT * FROM ".DB::table('dsu_medaltype'));
	$typeArr = array();
	while($typeinfo = DB::fetch($query)){
		$typeArr[] = $typeinfo;
	}
	
	$typeid = intval($_G['gp_typeid']);
	$sqladd = '';
	
	$sqladd .= $typeid > 0 ? " and mf.typeid = '$typeid'" : '';
	$query = DB::query("SELECT mf.*, m.* FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf USING(medalid) WHERE 1 $sqladd");
	$medallist = array();
	while($medal = DB::fetch($query)){
		$medalfieldSetting = (array)unserialize($medal['setting']);
		$medal['limit'] = '';
		foreach(getMedalExtendClass() as $classname => $newclass){
			if(method_exists($newclass, 'memcp_show')){
				$_limit = $newclass->memcp_show($medalfieldSetting[$classname]);
				if($_limit) $medal['limit'] .= $_limit."<br />";//"<p>$_limit</p>";
			}
		}
		$medallist[$medal['medalid']] = $medal;
	}
}else if($_G['gp_action'] == 'apply'){ //领取或申请勋章
	$medalid = intval($_G['gp_medalid']);
	$medal = DB::fetch_first("SELECT m.*, mf.* FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf ON m.medalid = mf.medalid WHERE m.medalid='$medalid'");
	
	if(!$medal['type']) {
		showmessage('medal_apply_invalid');
	}
	//检查是否已经领取过此勋章
	$medaldetail = DB::fetch_first("SELECT medalid FROM ".DB::table('forum_medallog')." WHERE uid='$_G[uid]' AND medalid='$medalid' AND type<'3'");
	if($medaldetail['medalid']) {
		showmessage('medal_apply_existence', $thisurl);
	}
	
	$applysucceed = TRUE;
	foreach(getMedalExtendClass() as $classname => $newclass){
		if(method_exists($newclass, 'memcp_check')) $applysucceed = $newclass->memcp_check();
	}
	
	if($applysucceed) {
		if($medal['type'] == 1) {
			$usermedal = DB::fetch_first("SELECT medals FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'");
			$medalnew = $usermedal['medals'] ? $usermedal['medals']."\t".$medal['medalid'] : $medal['medalid'];
			DB::query("UPDATE ".DB::table('common_member_field_forum')." SET medals='$medalnew' WHERE uid='$_G[uid]'");
			$medalmessage = 'medal_get_succeed';
		} else {
			$medalmessage = 'medal_apply_succeed';
		}

		$expiration = empty($medal['expiration'])? 0 : TIMESTAMP + $medal['expiration'] * 86400;
		DB::query("INSERT INTO ".DB::table('forum_medallog')." (uid, medalid, type, dateline, expiration, status) VALUES ('$_G[uid]', '$medalid', '$medal[type]', '$_G[timestamp]', '$expiration', '0')");
		showmessage($medalmessage, $thisurl, array('medalname' => $medal['name']));
	}
}
	
	

include template('dsu_medalCenter:memcp');
?>