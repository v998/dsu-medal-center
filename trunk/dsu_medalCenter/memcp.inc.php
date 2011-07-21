<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
!defined('IN_DISCUZ') && exit('Access Denied');

if(!$_G['uid'] && !(empty($_G['gp_action']) || $_G['gp_action'] == 'list')) showmessage('not_loggedin', NULL, array(), array('login'  =>  1));

require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/function_common.php';
include_once DISCUZ_ROOT.'./source/language/lang_template.php';
@include_once lang('medal');

$cvars = dsuMedal_phraseConfig();
$thisurl = 'plugin.php?id=dsu_medalCenter:memcp';
$navtitle = 'ѫ������';

$page = max(1, intval($_G['gp_page']));
$tpp = 20;
$start_limit = ($page - 1) * $tpp;


if(empty($_G['gp_action']) || $_G['gp_action'] == 'list'){
	//��ȡ������Ϣ
	$query = DB::query("SELECT * FROM ".DB::table('dsu_medaltype')." ORDER BY displayorder");
	$typeArr = array();
	while($typeinfo = DB::fetch($query)){
		$typeArr[] = $typeinfo;
	}
	$typeid = intval($_G['gp_typeid']);
	$thisurl .= $typeid > 0 ? "&typeid=$typeid" : '';
	
	$mymedal = getMedalByUid();

	$sqladd = '';
	$sqladd = " and available='1'";
	$sqladd .= $typeid > 0 ? " and mf.typeid = '$typeid'" : ''; //�Ƿ����Ʒ���
	$sqladd .= $_G['cookie']['dsu_medalCenter_hidemymedal'] ? " and m.medalid NOT IN ('".implode("','", $mymedal)."')" : ''; //�Ƿ������Լ�ӵ�е�ѫ��
	
	$num = DB::result_first("SELECT count(*) FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf USING(medalid) WHERE 1 $sqladd");
	$multipage = multi($num, $tpp, $page, $thisurl);
	
	$query = DB::query("SELECT mf.*, m.* FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf USING(medalid) WHERE 1 $sqladd ORDER BY m.displayorder LIMIT ".$start_limit." ,".$tpp);
	$medallist = array();
	while($medal = DB::fetch($query)){
		$medalfieldSetting = (array)unserialize($medal['setting']);
		$medal['limit'] = '';
		$medal['owned'] = in_array($medal['medalid'], $mymedal); 
		if($medal['type'] >= 1 && !$medal['owned']){ //ֻ�е�ѫ�������������ȡ�����Լ�û�д�ѫ��ʱ��ʾҪ��
			$medalid = $medal['medalid'];
			foreach(getMedalExtendClass() as $classname => $newclass){
				if(method_exists($newclass, 'memcp_show')){
					$_limit = $newclass->memcp_show($medalfieldSetting[$classname]);
					if($_limit) $medal['limit'] .= $_limit."<br />";//"<p>$_limit</p>";
				}
			}
		}
		$medallist[$medal['medalid']] = $medal;
	}
}else if($_G['gp_action'] == 'mymedal'){
	$thisurl .= '&action=mymedal';
	$medalShowLimit = intval($cvars['showMedalLimit'][$_G['groupid']]);
	$usermedalArr = getMedalByUid($_G['uid'], true);

	$mymedals = array();
	if($_G['gp_op'] == 'sethide' && $_G['gp_myMedalHide']){
		$myMedalHide = (array)$_G['gp_myMedalHide'];
		foreach($myMedalHide as $medalid => $value){
			if(($value == 1 || $value == 2) && isset($usermedalArr[$medalid])){
				$medalExpiration = $usermedalArr[$medalid];
				$medalExpiration = max(abs($medalExpiration), 1);
				$medalExpiration = $value == 1 ? -$medalExpiration : $medalExpiration;
				$usermedalArr[$medalid] = $medalExpiration == 1 ? 0 : $medalExpiration;
			}
		}
		
		$common = $newmedal = '';
		$medalShowCount = 0;
		foreach($usermedalArr as $medalid => $expiration){
			$newmedal .= $common.$medalid;
			if($medalShowLimit > 0 && $expiration >= 0 && (++$medalShowCount > $medalShowLimit)) {
				$expiration = $expiration == 0 ? -1 : -$expiration;
			}
			$newmedal .= $expiration != 0 ? '|'.$expiration : '';
			$common = "\t";
		}
		$i = 0;
		if($newmedal)
			DB::update('common_member_field_forum',array('medals'=>$newmedal),array('uid'=>$_G['uid']));
	}
	if($usermedalArr){
		$query = DB::query("SELECT * FROM ".DB::table('forum_medal')." WHERE medalid IN('".implode("','", array_keys($usermedalArr))."') and available='1'");
		$medalShowCount = 0;
		while($medal = DB::fetch($query)){
			$medal['expiration'] = $usermedalArr[$medal['medalid']];
			$medal['hide'] = $medal['expiration'] < 0 ? 1 : 2;
			if($medal['hide'] == 2) $medalShowCount++;
			$mymedals[$medal['medalid']] = $medal;
		}
	}
	$num = count($mymedals);
	
	$medallognum = DB::result_first("SELECT COUNT(*) FROM ".DB::table('forum_medallog')." WHERE uid='$_G[uid]' AND type<'2'");
	$multipage = multi($medallognum, $tpp, $page, $thisurl);

	$query = DB::query("SELECT me.*, m.image, m.name FROM ".DB::table('forum_medallog')." me
			LEFT JOIN ".DB::table('forum_medal')." m USING (medalid)
			WHERE me.uid='$_G[uid]' ORDER BY me.dateline DESC LIMIT $start_limit,$tpp");
	$medallog = array();
	while($medallog = DB::fetch($query)) {
		$medallog['dateline'] = dgmdate($medallog['dateline']);
		$medallog['expiration'] = !empty($medallog['expiration']) ? dgmdate($medallog['expiration'], 'd') : '';
		$medallogs[] = $medallog;
	}
}else if($_G['gp_action'] == 'apply'){ //��ȡ������ѫ��
	$medalid = intval($_G['gp_medalid']);
	$medal = DB::fetch_first("SELECT m.*, mf.* FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf USING(medalid) WHERE m.medalid='$medalid'");

	if(empty($medal) || !$medal['available']) {
		showmessage('�Բ��𣬲����ڶ�Ӧѫ�µ����ݣ��뷵�ء�');
	}

	if(!$medal['type']) {
		showmessage('medal_apply_invalid');
	}
	//����Ƿ��Ѿ���ȡ����ѫ��
	$usermedalArr = getMedalByUid($_G['uid'], true);
	if(isset($usermedalArr[$medalid]) && (abs($usermedalArr[$medalid]) <= 1 || abs($usermedalArr[$medalid]) >= TIMESTAMP)){ //�����ǰ�û�ѫ�����д�ѫ��
		showmessage('medal_apply_existence', $thisurl);
	}else{ //����Ƿ��г��������ѫ��
		//$query = DB::query("SELECT medalid,type FROM ".DB::table('forum_medallog')." WHERE uid='$_G[uid]' AND medalid='$medalid' ORDER BY dateline");
		$medaldetail = DB::fetch_first("SELECT medalid FROM ".DB::table('forum_medallog')." WHERE uid='$_G[uid]' AND medalid='$medalid' AND type = 2");
		if($medaldetail['medalid']) showmessage('medal_apply_existence', $thisurl);
	}

	
	$applysucceed = TRUE;
	$medalfieldSetting = (array)unserialize($medal['setting']);
	foreach(getMedalExtendClass() as $classname => $newclass){
		if($applysucceed && method_exists($newclass, 'memcp_check')) $applysucceed = $newclass->memcp_check($medalfieldSetting[$classname]);
		list($applysucceed, $msg) = is_array($applysucceed) ? $applysucceed : array($applysucceed);
		if($applysucceed !== TRUE){
			$msg = empty($msg) ? "�Բ�����������δ������������������ʧ�ܣ��뷵�ء�" : $msg;
			showmessage($msg);
		}
	}
	if($applysucceed) {
		$expiration = empty($medal['expiration'])? 0 : TIMESTAMP + $medal['expiration'] * 86400;
		if($medal['type'] == 1 || $medal['type'] == 5) {
			$usermedal = implode("\t", getMedalByUid($_G['uid']));

			$medalShowLimit = $cvars['showMedalLimit'][$_G['groupid']];
			if($medalShowLimit > 0){ //�������ѫ��չʾ����
				$count = 0;
				foreach($usermedalArr as $_medalid => $_expiration) if( $_expiration >= 0) $count++; //ͳ����ʾ��ѫ������
				if($count >= $medalShowLimit) $expiration = $expiration ? -$expiration : -1; //����ѫ��Ĭ�ϲ���ʾ
			}
			$medalid = $medalid.(empty($expiration) ? '' : '|'.$expiration);
			$expiration = abs($expiration) > 1 ? abs($expiration) : 0;
			$medalnew = $usermedal ? $usermedal."\t".$medalid : $medalid;
			DB::query("UPDATE ".DB::table('common_member_field_forum')." SET medals='$medalnew' WHERE uid='$_G[uid]'");
			foreach(getMedalExtendClass() as $classname => $newclass){
				if(method_exists($newclass, 'memcp_get_succeed')) $newclass->memcp_get_succeed($medalfieldSetting[$classname]);
			}
			$medalmessage = 'medal_get_succeed';
			//$medal['type'] = 1;
		} else {
			//foreach(getMedalExtendClass() as $classname => $newclass){
			//	if(method_exists($newclass, 'memcp_apply_succeed')) $newclass->memcp_apply_succeed($medalfieldSetting[$classname]);
			//}
			$medalmessage = 'medal_apply_succeed';
			manage_addnotify('verifymedal');
		}
		DB::query("INSERT INTO ".DB::table('forum_medallog')." (uid, medalid, type, dateline, expiration, status) VALUES ('$_G[uid]', '$medalid', '$medal[type]', '$_G[timestamp]', '$expiration', '0')");
		showmessage($medalmessage, $thisurl, array('medalname' => $medal['name']));
	}else{
		showmessage("�Բ�����������δ������������������ʧ�ܣ��뷵�ء�");
	}
}
	
	

include template('dsu_medalCenter:memcp');
?>