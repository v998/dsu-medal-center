<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_czw_premedal{
	
	var $name = '��ȡ����ģ�� For ����ѫ��'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.jhdxr.com">������Ϻ��@DSU</a>';
	var $introduction = '����������ȡĳ��ѫ��ǰ����ӵ����һ��ѫ��';
	
	private $setting = array();
	
	function admincp_show_simple($setting){
		global $medalid;
		$setting['preMedalid'] = intval($setting['preMedalid']);
		$varname = array('preMedalid', array());
		$varname[1][] = array(0, '������');
		$query = DB::query("SELECT medalid, name FROM ".DB::table('forum_medal')." ORDER BY displayorder");
		while($medal = DB::fetch($query)) {
			if($medalid != $medal['medalid']) $varname[1][] = array($medal['medalid'], $medal['name']);
		}
		showsetting('ӵ��ѫ��', $varname, $setting['preMedalid'], 'select', '', '', '����ȡ��ѫ��ǰ��Ҫӵ�е�ѫ��');
	}

	function admincp_check(){
	}
	
	function admincp_save(){
		global $_G;
		return array(
			'preMedalid' => $_G['gp_preMedalid'],
		);
	}
	
	function memcp_show($setting){
		$return = '';
		$this->setting = $setting;
		if($setting['preMedalid']){
			$medal = DB::fetch_first("SELECT name FROM ".DB::table('forum_medal')." WHERE medalid='$setting[preMedalid]'");
			$_check = $this->_memcpCheck();
			$return .= '<font color="'.($_check ? 'green' : 'red').'">';
			$return .= '<strong>ӵ��ѫ�£�</strong><br />';
			$return .= $medal['name'];
			$return .= '</font>';
		}
		return $return;
	}
	
	function memcp_check($setting){
		$this->setting = $setting;
		return $this->_memcpCheck();
	}
	
	private function _memcpCheck(){
		$setting = $this->setting;
		if($setting['preMedalid'] && !in_array($setting['preMedalid'], getMedalByUid())) return false;
		return true;
	}

}
?>