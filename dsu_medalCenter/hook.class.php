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