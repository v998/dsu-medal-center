<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_market{

	var $name = '积分购买模块';
	var $version = '1.0';
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	var $introduction = '';
	
	var $setting = array();
	
	function admincp_show($setting){
		global $_G;
		$creditset = $setting['MarketCredit'];
		showtableheader('积分价格', 'notop', 'id="creditbody"');
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
	}
	
	function memcp_get_succeed($setting){
		
	}
	
	function memcp_show($setting){
		global $_G;
		$this->setting = $setting;
		$return = '';
		if($setting['MarketCredit']){
			$return .= '<strong>勋章价格：</strong><br />';
			$common = '';
			foreach($setting['MarketCredit'] as $creditid => $value){
				$return .= $common;
				$return .= $value > 0 ? $_G['setting'][extcredits][$creditid]['title'].':'.$value : '';
				$common = '<br />';
			}
		}
		return $return;
	}
}
?>