<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
!defined('IN_DISCUZ') && exit('Access Denied');

class plugin_dsu_medalCenter{
	private $identifier = 'dsu_medalCenter';
	private $cvars=array();

	public function __construct() {
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
	public function __construct(){
		parent::__construct();
	}
	
	
	//&#21520;&#27133;&#65306;&#35813;&#27515;&#30340;discuz&#19981;&#30693;&#36947;&#24590;&#20040;&#20462;&#25913;&#20102;&#23884;&#20837;&#28857;&#30340;&#21629;&#21517;&#35268;&#21017;&#65292;&#23548;&#33268;&#20986;&#29616;&#20102;&#36825;&#20040;&#35809;&#24322;&#30340;&#20989;&#25968;&#21517;&#12290;&#12290;&#12290;
	public function space_(){
		global $_G;
		if($_G['gp_mod']=='medal'){
			//echo '<script>window.location.href ="plugin.php?id=dsu_medalCenter:memcp"</script>';
			//exit();
		}
	}
}
?>