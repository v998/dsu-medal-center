<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_market{

	var $name = '���ֹ���ģ��';
	var $version = '1.1';
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	var $introduction = '';
	
	var $setting = array();
	
	function admincp_show($setting){
		global $_G;
		$creditset = $setting['MarketCredit'];
		showtableheader('���ּ۸�', 'notop', 'id="creditbody"');
		include template('dsu_medalCenter:admin_extcredit');
		showtablefooter();
	}
	
	function admincp_check(){
		global $_G;
		$creditArr = array();
		if($_G['gp_typenew'] == 5){
			foreach($_G['gp_newcredit'] as $creditid => $value){
				$creditArr[$creditid] = intval($value);
			}
		}
		$_G['gp_newcredit'] = $creditArr;
	}
	
	function admincp_save(){
		global $_G;
		return array('MarketCredit' => $_G['gp_newcredit']);
	}
	
	function memcp_check($setting){
		if($setting['MarketCredit']){
			$return = self::_checkCredit($setting['MarketCredit']);
			if(!$return)
				return '�Բ������������ֲ��㣬����ʧ�ܣ��뷵�ء�';
		}
		return true;
	}
	
	function memcp_get_succeed($setting){
		global $_G;
		if($setting['MarketCredit']){
			$creditArr = $setting['MarketCredit'];
			foreach($creditArr as $id => &$value){
				$value *= -1;
			}
			updatemembercount($_G['uid'], $creditArr);
		}
	}
	
	function memcp_show($setting){
		global $_G;
		$this->setting = $setting;
		$return = '';
		if($setting['MarketCredit']){
			$return .= '<strong>ѫ�¼۸�</strong><br />';
			$common = '';
			foreach($setting['MarketCredit'] as $creditid => $value){
				$return .= $common;
				$return .= $value > 0 ? $_G['setting'][extcredits][$creditid]['title'].':'.$value : '';
				$common = '<br />';
			}
		}
		return $return;
	}
	
	function _checkCredit($creditid, $value=0){
		global $_G;
		if(is_array($creditid)){
			foreach($creditid as $id=>$value){
				if(!self::_checkCredit($id, $value)) return false;
			}
			return true;
		}else{
			unset($_G['member']['extcredits'.$creditid]);
			getuserprofile('extcredits'.$creditid);
			return $value ==0 || $_G['member']['extcredits'.$creditid] >= $value;
		}
	}
	
	function uninstall(){
		return array(false, 'ϵͳģ�飬��ֹж�أ�');
	}
}
?>