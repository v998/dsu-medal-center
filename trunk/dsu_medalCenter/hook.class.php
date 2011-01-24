<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
!defined('IN_DISCUZ') && exit('Access Denied');

class plugin_dsu_medalCenter{
	var $identifier = 'dsu_medalCenter';
	var $cvars=array();

	function __construct() {
		global $_G;
		$this->cvars = $_G['cache']['plugin']['dsu_medalCenter'];
	}
	
	/*function global_header() {
		global $_G;
		if(defined('CURSCRIPT') && CURSCRIPT == 'admin'){
			if($_G['gp_action'] == 'medals'){
				$url = 'admin.php?frames=yes&action=plugins&operation=config&identifier=dsu_medalCenter&pmod=';
				switch($_G['gp_operation']){
					case 'mod':
						$url .= 'admin_mod';
						break;
					default:
						$url .= 'admin_manage';
				}
				echo '<script>window.location.href ="'.$url.'"</script>';
			}
		}
	}*/
}

class plugin_dsu_medalCenter_home extends plugin_dsu_medalCenter{
	function __construct(){
		parent::__construct();
	}
	
	function medal_autoforward_output(){
		global $_G;
		if($_G['gp_mod']=='medal'){
			echo '<script>window.location.href ="plugin.php?id=dsu_medalCenter:memcp"</script>';
		}
	}
}
?>