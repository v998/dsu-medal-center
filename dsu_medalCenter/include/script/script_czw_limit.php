<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_czw_limit{
	
	var $name = '��ȡ����ģ��'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.jhdxr.com">������Ϻ��@DSU</a>';
	var $introduction = '���Զ�ѫ�µ���ȡʱ�䡢��ȡ������������';
	
	
	function admincp_show_simple($setting){
		$setting['startTime'] = $setting['startTime'] ? dgmdate($setting['startTime'], 'd') : '';
		$setting['endTime'] = $setting['endTime'] ? dgmdate($setting['endTime'], 'd') : '';
		$setting['getCountLimit'] = intval($setting['getCountLimit']);
		showsetting('����ʱ��(����):', 'startTime', $setting['startTime'], 'calendar', '', '', 'ѫ�¿�ʼ��ȡʱ�䣬���ձ�ʾ������ʼ');	
		showsetting('����ʱ��(����):', 'endTime', $setting['endTime'], 'calendar', '', '', '�������Ա���޷���ȡ/�����ѫ�£����ձ�ʾ������');	
		showsetting('��ȡ�˴�����', 'getCountLimit', $setting['getCountLimit'], 'number', '', '', '����ȡ/������˴δﵽ�����ֵʱ��ϵͳ���Զ��ܾ��µ����룬0 ������Ϊ������');	
	}

	function admincp_check(){
		global $_G;
		$_G['gp_getCountLimit'] = intval($_G['gp_getCountLimit']);
		$_G['gp_startTime'] = $_G['gp_startTime'] ? strtotime($_G['gp_startTime']) : 0;
		$_G['gp_endTime'] = $_G['gp_endTime'] ? strtotime($_G['gp_endTime']) : 0;
		if($_G['gp_getCountLimit'] < 0) cpmsg('��ȡ��������Ϊ�������뷵�ء�', '', 'error');
		if($_G['gp_endTime'] && $_G['gp_startTime'] && $_G['gp_startTime'] > $_G['gp_endTime'])  cpmsg('����ʱ��������ڿ�ʼʱ�䣡�뷵�ء�', '', 'error');
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
			$return .= '<strong>��ȡʱ�䣺</strong><br />';
			$return .= $setting['startTime'] ? dgmdate($setting['startTime'], 'd').'��' : '';
			$return .= $setting['endTime'] ? dgmdate($setting['endTime'], 'd').'ֹ' : '';
			$return .= '</font>';
		}
		return $return;
	}
	
	function memcp_check($setting){
		return $this->_memcpCheckTime($setting);
	}
	
	/**
	 * ����չ�ű���װʱ���Զ����ô˷�����
	 */
	function install(){}
	
	/**
	 * ����չ�ű�����ʱ���Զ����ô˷�����
	 */
	function upgrade(){}
	
	/**
	 * ����չ�ű�ж��ʱ���Զ����ô˷�����
	 */
	function uninstall(){}
	
	private function _memcpCheckTime($setting){
		if($setting['startTime'] && $setting['startTime'] > TIMESTAMP) return false;
		if($setting['endTime'] && $setting['endTime'] < TIMESTAMP) return false;
		return true;
	}
}
?>