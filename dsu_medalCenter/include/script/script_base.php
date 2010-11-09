<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_base{
	
	var $name = '范例程序'; //扩展脚本名
	var $version = '1.0'; //扩展脚本版本号
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	
	
	
	/**
	 * 显示设置时的内容
	 */
	function admincp_show(){}
	
	/**
	 * 在数据提交后对数据进行合法性检验
	 */
	function admincp_check(){}
	
	/**
	 * @return <array>返回要保存的内容
	 */
	function admincp_save(){
		return array();
	}
	
	/**
	 * @return <bool>返回检验是否通过
	 */
	function memcp_check(){}
	
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
}
?>