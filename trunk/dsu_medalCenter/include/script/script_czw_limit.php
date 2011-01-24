<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_czw_limit{
	
	var $name = '��ȡ����ģ�� For ʱ�䡢����'; //��չ�ű���
	var $version = '1.1'; //��չ�ű��汾��
	var $copyright = '<a href="www.jhdxr.com">������Ϻ��@DSU</a>';
	var $introduction = '���Զ�ѫ�µ���ȡʱ�䡢��ȡ������������';
	
	private $setting = array();
	
	function admincp_show_simple($setting){
		$setting['startTime'] = $setting['startTime'] ? dgmdate($setting['startTime'], 'd') : '';
		$setting['endTime'] = $setting['endTime'] ? dgmdate($setting['endTime'], 'd') : '';
		$setting['getCountLimit'] = intval($setting['getCountLimit']);
		showsetting('����ʱ��(����):', 'startTime', $setting['startTime'], 'calendar', '', '', 'ѫ�¿�ʼ��ȡʱ�䣬���ձ�ʾ������ʼ');	
		showsetting('����ʱ��(����):', 'endTime', $setting['endTime'], 'calendar', '', '', '�������Ա���޷���ȡ/�����ѫ�£����ձ�ʾ������');	
		showsetting('��ȡ�˴�����', 'getCountLimit', $setting['getCountLimit'], 'number', '', '', '����ȡ���˴δﵽ�����ֵʱ��ϵͳ���Զ��ܾ��µ���ȡ��0 ������Ϊ�����ơ���ע����ѡ������Զ�������Ч����');	
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
		$this->setting = $setting;
		if($setting['startTime'] || $setting['endTime']){
			$_check = $this->_memcpCheckTime();
			$return .= '<font color="'.($_check ? 'green' : 'red').'">';
			$return .= '<strong>��ȡʱ�䣺</strong><br />';
			$return .= $setting['startTime'] ? dgmdate($setting['startTime'], 'd').'��' : '';
			$return .= $setting['endTime'] ? dgmdate($setting['endTime'], 'd').'ֹ' : '';
			$return .= '</font>';
			if($setting['getCountLimit']) $return .= '<br />';
		}
		if($setting['getCountLimit']){
			$_check = $this->_memcpCheckCount();
			$return .= '<font color="'.($_check ? 'green' : 'red').'">';
			$return .= '<strong>��ȡ�������ƣ�</strong><br />';
			$return .= $this->_MedalCount().'/'.$setting['getCountLimit'];
			$return .= '</font>';
		}
		return $return;
	}
	
	function memcp_check($setting){
		$this->setting = $setting;
		return $this->_memcpCheckTime() && $this->_memcpCheckCount();
	}
	
	function memcp_get_succeed($setting){
		$this->_MedalCount(0, true);
	}
	
	private function _memcpCheckTime(){
		$setting = $this->setting;
		if($setting['startTime'] && $setting['startTime'] > TIMESTAMP) return false;
		if($setting['endTime'] && $setting['endTime'] < TIMESTAMP) return false;
		return true;
	}
	
	private function _memcpCheckCount(){
		$setting = $this->setting;
		if($setting['getCountLimit']){
			if($this->_MedalCount() >= $setting['getCountLimit'])
				return false;
		}
		return true;
	}
	
	private function _MedalCount($_medalid = 0, $inc = false){
		global $medalid;
		$_medalid = $_medalid ? $_medalid : $medalid;
		$data = dsuMedal_getOption($_medalid, 'script_czw_limit');
		$count = $data ? $data['data'] : 0;
		if($inc) dsuMedal_saveOption($_medalid, ++$count, 'script_czw_limit');
		return $count;
	}
}
?>