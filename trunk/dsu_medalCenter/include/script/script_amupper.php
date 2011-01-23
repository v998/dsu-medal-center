<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id: script_example.php 29 2011-01-15 13:35:57Z chuzhaowei@gmail.com $
*/
class script_amupper{
	
	var $name = '�򿨻���չģ��'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	var $introduction = '�����ȡѫ��ʱ�����������ۼƴ�ǩ��������Ҫ��'; //������������д�������չ�Ľ���
	
	
	
	/**
	 * ����ʱ��ʾ�����ݣ�ֱ���ں�����������ɣ�
	 * �˺������� admincp_show_simple ���ã�һ��������ʾ����ö���table��������
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 */
	function admincp_show($setting){
		return '';
	}
	
	/**
	 * ����ʱ��ʾ�����ݣ�ֱ���ں�����������ɣ�
	 * �˺������� admincp_show ���ã�һ��������ʾ������ö���table��������
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 */
	function admincp_show_simple($setting){
		global $_G, $lang;
		$var = array();
		$var['value'] = $setting['ppercon'];
		$var['type'] = '<input name="ppercon" value="'.$var['value'].'" type="number" class="txt">';
		
		showsetting('�򿨻�-����ǩ������', '', '', $var['type'], '', '', '��������ǩ���������õ��û���ȡѫ�¡�');

		$var2 = array();
		$var2['value'] = $setting['pperaddup'];
		$var2['type'] = '<input name="pperaddup" value="'.$var2['value'].'" type="number" class="txt">';
		
		showsetting('�򿨻�-�ۼ�ǩ������', '', '', $var2['type'], '', '', '�����ۼ�ǩ���������õ��û���ȡѫ�¡�');
	}
	
	/**
	 * �������ύ������ݽ��кϷ��Լ���
	 */
	function admincp_check(){
		global $_G, $medalid;
		$ppercon = intval($_G['gp_ppercon']);
		if($ppercon >= 0){}else{cpmsg('�򿨻�-����ǩ���������ô���', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid='.$medalid ,'error');}	
		$pperaddup = intval($_G['gp_pperaddup']);
		if($pperaddup >= 0){}else{cpmsg('�򿨻�-�ۼ�ǩ���������ô���', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid='.$medalid ,'error');}			
	}
	
	/**
	 * @return <array>����Ҫ���������
	 */
	function admincp_save(){
		global $_G;
		return array('ppercon' => $_G['gp_ppercon'],'pperaddup' => $_G['gp_pperaddup']);
	}
	
	/**
	 * ǰ̨ѫ���б�ʱ��ʾ������Ҫ��
	 * ��������������ø�ʽ��
	 * 		�������⣺����
	 * 		�������ݣ���������������ʾΪ��ɫ�������������ʾΪ��ɫ
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 * @return <string>����Ҫ��ʾ������
	 */
	function memcp_show($setting){
		global $_G;
		$return = '';
		if($setting['ppercon'] && $setting['pperaddup']){
			$_check = $this->_memcp_check($setting);
			$return .= ($_check == 1 || $_check == 3  ? '' : '<font color="red">');
			$return .= '<strong>������ǩ�����ڵ��ڣ�</strong>';
			$return .= ($_check == 1 || $_check == 3  ? '' : '</font>');
			$return .= $setting['ppercon'].'��';
			$return .= ($_check == 2 || $_check == 3  ? '' : '<font color="red">');
			$return .= '<BR><strong>�ۼƴ�ǩ�����ڵ��ڣ�</strong>';
			$return .= ($_check == 2 || $_check == 3  ? '' : '</font>');
			$return .= $setting['pperaddup'].'��';
		}
		return $return;
	}
	
	/**
	 * �����û��Ƿ�������ȡҪ��
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 * @return <bool>���ؼ����Ƿ�ͨ��
	 */
	function memcp_check($setting){
		global $_G;
		$_check = $this->_memcp_check($setting);
		$return = ($_check == 3 ? '1' : '0');
		return $return;
	}

	function _memcp_check($setting){
		global $_G;
		$return = 0;
		$cdb_pper['uid'] = intval($_G['uid']);
		$query = DB::fetch_first("SELECT * FROM ".DB::table("plugin_dsuampper")." WHERE uid = '{$cdb_pper['uid']}'");
		if(empty($setting['ppercon']) || $query['continuous'] >= $setting['ppercon']){$return = $return + 1;}
		if(empty($setting['pperaddup']) || $query['addup'] >= $setting['pperaddup']){$return = $return + 2;}
		return $return;
	}

	function memcp_get_succeed($setting){
		return;
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
}
?>