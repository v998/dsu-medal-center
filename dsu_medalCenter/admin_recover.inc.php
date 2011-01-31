<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id: admin_type.inc.php 7 2010-11-10 01:51:23Z chuzhaowei@gmail.com $
*/
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');

if(submitcheck('medalsubmit')){
	//PRINT_R($_POST);
	loadcache('dsuMedalCenter');
	$mdshow = mdshow($_G['gp_medals'],$_G['cache']['dsuMedalCenter']);
	$_G['gp_medals'] = intval($_G['gp_medals']);
	if(!$_G['gp_ok'] && $_G['gp_username'] && $_G['gp_medals']){
		$username = $_G['gp_username'] == '*'?'所以会员':$_G['gp_username'];
		showformheader("plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_recover", '', 'configform');
		showtips('<li>用户名输入框可使用通配符 *，多个值之间用半角逗号“,”分隔。</li><li>当用户名输入&nbsp;*&nbsp;可以删除所有会员的某一个或所有勋章。</li><li></li>');
		showtableheader('您确定删除&nbsp;'.$username.'&nbsp;的以下勋章吗？');
		
		echo '<tr><td colspan="15" >'.$mdshow.'<input name="username" value="'.$_G['gp_username'].'" type="hidden"><input name="medals" value="'.$_G['gp_medals'].'" type="hidden"><input name="ok" value="1" type="hidden"></td></tr>';
		showsubmit('medalsubmit', "确定");
		showformfooter();
		showtablefooter();
	}elseif($_G['gp_ok'] && $_G['gp_username'] && $_G['gp_medals']){
		$search_condition = array_merge($_GET, $_POST);

		foreach($search_condition as $k => $v) {
			if(in_array($k, array('action', 'operation', 'formhash', 'medalsubmit', 'page', 'identifier', 'pmod', 'medals', 'ok')) || $v === '') {
				unset($search_condition[$k]);
			}
		}
		$usernames = searchmembers($search_condition);
		//PRINT_R($usernames);
		if($usernames && $_G['gp_medals'] && $_G['gp_medals'] != '-1'){
			$conditions = 'uid IN ('.dimplode($usernames).')';
			DB::query("UPDATE ".DB::table('common_member_field_forum')." SET medals=replace(medals, '".$_G['gp_medals']."','') WHERE medals LIKE '".$_G['gp_medals']."%' AND ".$conditions);
			DB::query("DELETE FROM ".DB::table('forum_medallog')." WHERE medalid='".$_G['gp_medals']."' AND type<'3' AND ".$conditions);
		}

	}
	cpmsg('成功回收！', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_recover', 'succeed');
}else{
	loadcache('dsuMedalCenter');
	$mdsel = md2seled($_G['cache']['dsuMedalCenter']);
	showformheader("plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_recover", '', 'configform');
	showtips('<li>用户名输入框可使用通配符 *，多个值之间用半角逗号“,”分隔。</li><li>当用户名输入&nbsp;*&nbsp;可以删除所有会员的某一个或所有勋章。</li><li></li>');
	showtableheader('勋章回收');
	showsetting("用户名 ", 'username', 'root,111111', 'text');
	echo '<tr><td colspan="2" class="td27">回收勋章 :</td></tr><tr class="noborder"><td class="vtop rowform">'.$mdsel.'</td><td class="vtop tips2"></td></tr>';
	showsubmit('medalsubmit', "确定");
	showformfooter();
	showtablefooter();
}


function md2seled($array){
	$select_out = '<select name="medals">';
	$select_out .='<option value="-1">所有勋章</option>' ;
	foreach($array as $i => $value){
		$select_out .='<option value="'.$i.'">'.$value['name'].'</option>' ;
	}
	$select_out .= '</select>';
	return $select_out;
}
function mdshow($k,$array){
	if($k == '-1'){
		foreach($array as $i => $value){
			$show_out .='<img style="vertical-align:middle" src="static/image/common/'.$value['image'].'">&nbsp;'.$value['name'].'&nbsp;&nbsp;&nbsp;&nbsp;' ;
		}
	}else{
		$show_out ='<img style="vertical-align:middle" src="static/image/common/'.$array[$k]['image'].'">&nbsp;'.$array[$k]['name'].'&nbsp;&nbsp;&nbsp;&nbsp;' ;
	}
	return $show_out;
}

function searchmembers($condition, $limit=2000, $start=0) {
	include_once libfile('class/membersearch');
	$ms = new membersearch();
	return $ms->search($condition, $limit, $start);
}
?>