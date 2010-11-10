<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_usergroup {
	
	var $name = '用户组限制模块';
	var $version = '1.0';
	var $copyright = '<a href="www.jhdxr.com">江湖大虾仁@DSU</a>';
	
	function admincp_show($setting){
		global $_G, $lang;
		$var = array();
		$var['value'] = $setting['usergroup'];
		showtableheader('允许领取勋章的用户组', 'notop');
		$query = DB::query("SELECT type, groupid, grouptitle, radminid FROM ".DB::table('common_usergroup')." ORDER BY (creditshigher<>'0' || creditslower<>'0'), creditslower, groupid");
		$groupselect = array();
		while($group = DB::fetch($query)) {
			$group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
			$groupselect[$group['type']] .= '<option value="'.$group['groupid'].'"'.(@in_array($group['groupid'], $var['value']) ? ' selected' : '').'>'.$group['grouptitle'].'</option>';
		}
		$var['type'] = '<select name="usergroup[]" size="10" multiple="multiple"><option value=""'.(@in_array('', $var['value']) ? ' selected' : '').'>'.cplang('plugins_empty').'</option>';
		$var['type'] .= '<optgroup label="'.$lang['usergroups_member'].'">'.$groupselect['member'].'</optgroup>'.
			($groupselect['special'] ? '<optgroup label="'.$lang['usergroups_special'].'">'.$groupselect['special'].'</optgroup>' : '').
			($groupselect['specialadmin'] ? '<optgroup label="'.$lang['usergroups_specialadmin'].'">'.$groupselect['specialadmin'].'</optgroup>' : '').
			'<optgroup label="'.$lang['usergroups_system'].'">'.$groupselect['system'].'</optgroup></select>';
		
		showsetting('用户组', '', '', $var['type']);
		showtablefooter();
	}
	
	function admincp_check(){
		global $_G;
		$usergroup = &$_G['gp_usergroup'];
		if(in_array('', $usergroup)) $usergroup = array('');
	}
	
	function admincp_save(){
		global $_G;
		return array('usergroup' => $_G['gp_usergroup']);
	}
}
?>