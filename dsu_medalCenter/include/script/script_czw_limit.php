<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_czw_limit{
	
	var $name = '领取限制模块'; //扩展脚本名
	var $version = '1.0'; //扩展脚本版本号
	var $copyright = '<a href="www.jhdxr.com">江湖大虾仁@DSU</a>';
	var $introduction = '可以对勋章的领取时间、领取数量进行限制';
	
	
	function admincp_show_simple($setting){
		$setting['startTime'] = $setting['startTime'] ? dgmdate($setting['startTime'], 'd') : '';
		$setting['endTime'] = $setting['endTime'] ? dgmdate($setting['endTime'], 'd') : '';
		$setting['getCountLimit'] = intval($setting['getCountLimit']);
		showsetting('上线时间(日期):', 'startTime', $setting['startTime'], 'calendar', '', '', '勋章开始领取时间，留空表示立即开始');	
		showsetting('下线时间(日期):', 'endTime', $setting['endTime'], 'calendar', '', '', '结束后会员将无法领取/申请此勋章，留空表示不限制');	
		showsetting('获取人次上限', 'getCountLimit', $setting['getCountLimit'], 'number', '', '', '当领取/申请的人次达到这个数值时，系统将自动拒绝新的申请，0 或留空为不限制');	
	}

	function admincp_check(){
		global $_G;
		$_G['gp_getCountLimit'] = intval($_G['gp_getCountLimit']);
		$_G['gp_startTime'] = $_G['gp_startTime'] ? strtotime($_G['gp_startTime']) : 0;
		$_G['gp_endTime'] = $_G['gp_endTime'] ? strtotime($_G['gp_endTime']) : 0;
		if($_G['gp_getCountLimit'] < 0) cpmsg('领取数量必须为正数！请返回。', '', 'error');
		if($_G['gp_endTime'] && $_G['gp_startTime'] && $_G['gp_startTime'] > $_G['gp_endTime'])  cpmsg('结束时间必须晚于开始时间！请返回。', '', 'error');
	}
	
	function admincp_save(){
		global $_G;
		return array(
			'getCountLimit' => $_G['gp_getCountLimit'],
			'startTime' => $_G['gp_startTime'],
			'endTime' => $_G['gp_endTime'],
		);
	}
	
	function memcp_show($setting){
		$return = '';
		if($setting['startTime'] || $setting['endTime']){
			$_check = $this->_memcpCheckTime($setting);
			$return .= '<font color="'.($_check ? 'green' : 'red').'">';
			$return .= '<strong>领取时间：</strong><br />';
			$return .= $setting['startTime'] ? dgmdate($setting['startTime'], 'd').'起' : '';
			$return .= $setting['endTime'] ? dgmdate($setting['endTime'], 'd').'止' : '';
			$return .= '</font>';
		}
		return $return;
	}
	
	function memcp_check($setting){
		return $this->_memcpCheckTime($setting);
	}
	
	/**
	 * 在扩展脚本安装时会自动调用此方法。
	 */
	function install(){}
	
	/**
	 * 在扩展脚本升级时会自动调用此方法。
	 */
	function upgrade(){}
	
	/**
	 * 在扩展脚本卸载时会自动调用此方法。
	 */
	function uninstall(){}
	
	private function _memcpCheckTime($setting){
		if($setting['startTime'] && $setting['startTime'] > TIMESTAMP) return false;
		if($setting['endTime'] && $setting['endTime'] < TIMESTAMP) return false;
		return true;
	}
}
?>