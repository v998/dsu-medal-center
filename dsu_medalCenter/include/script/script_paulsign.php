<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_paulsign{
	
	var $name = '[DSU]ÿ��ǩ����չģ��'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.dsu.cc">Shy9000&nbsp;@&nbsp;DSU</a>';
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
		$var['type'] = '<input name="month_sign" value="'.$var['value'].'" type="number" class="txt">';
		
		showsetting('ÿ��ǩ��-��ǩ������', '', '', $var['type'], '', '', '��������ǩ���������õ��û���ȡѫ�¡�');

		$var2 = array();
		$var2['value'] = $setting['pperaddup'];
		$var2['type'] = '<input name="all_sign" value="'.$var2['value'].'" type="number" class="txt">';
		
		showsetting('ÿ��ǩ��-��ǩ������', '', '', $var2['type'], '', '', '�����ۼ�ǩ���������õ��û���ȡѫ�¡�');
	}
	
	/**
	 * �������ύ������ݽ��кϷ��Լ���
	 */
	function admincp_check(){
		global $_G, $medalid;
		$month_sign = is_numeric($_G['gp_month_sign']);
		if($month_sign || empty($month_sign)){}else{cpmsg('ÿ��ǩ��-��ǩ���������ô���', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid='.$medalid ,'error');}
		
		$all_sign = is_numeric($_G['gp_all_sign']);
		if($all_sign || empty($all_sign)){}else{cpmsg('ÿ��ǩ��-��ǩ���������ô���', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid='.$medalid ,'error');}			
	}
	
	/**
	 * @return <array>����Ҫ���������
	 */
	function admincp_save(){
		global $_G;
		return array('month_sign' => $_G['gp_month_sign'],'all_sign' => $_G['gp_all_sign']);
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
		if($setting['month_sign'] && $setting['all_sign']){
			$_check = $this->_memcp_check($setting);
			$return .= ($_check == 1 || $_check == 3  ? '' : '<font color="red">');
			$return .= '<strong>������ǩ�����ڵ��ڣ�</strong>';
			$return .= ($_check == 1 || $_check == 3  ? '' : '</font>');
			$return .= $setting['month_sign'].'��';

			$return .= ($_check == 2 || $_check == 3  ? '' : '<font color="red">');
			$return .= '<BR><strong>�ۼƴ�ǩ�����ڵ��ڣ�</strong>';
			$return .= ($_check == 2 || $_check == 3  ? '' : '</font>');
			$return .= $setting['all_sign'].'��';
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
		$return = ($_check == 3 ? TRUE : FALSE);
		return $return;
	}

	function _memcp_check($setting){
		global $_G;
		$return = 0;
		$qiandaodb = DB::fetch_first("SELECT * FROM ".DB::table('dsu_paulsign')." WHERE uid='$_G[uid]'");
		if(empty($setting['month_sign']) || $qiandaodb['mdays'] >= $setting['month_sign']){$return = $return + 1;}
		if(empty($setting['all_sign']) || $qiandaodb['days'] >= $setting['all_sign']){$return = $return + 2;}
		return $return;
	}

}
?>