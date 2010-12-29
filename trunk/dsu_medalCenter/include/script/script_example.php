<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_example{
	
	var $name = '范例程序'; //扩展脚本名
	var $version = '1.0'; //扩展脚本版本号
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	//var $introduction = '在这儿你可以填写对这个扩展的介绍';
	
	
	
	/**
	 * 设置时显示的内容（直接在函数中输出即可）
	 * 此函数后于 admincp_show_simple 调用，一般用于显示需采用独立table的设置项
	 * @param <array> $setting 传入admincp_save方法中保存的信息
	 */
	function admincp_show($setting){}
	
	/**
	 * 设置时显示的内容（直接在函数中输出即可）
	 * 此函数先于 admincp_show 调用，一般用于显示无需采用独立table的设置项
	 * @param <array> $setting 传入admincp_save方法中保存的信息
	 */
	function admincp_show_simple($setting){}
	
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
	 * 前台勋章列表时显示的设置要求
	 * 建议采用如下设置格式：
	 * 		条件标题：粗体
	 * 		条件内容：如果满足的条件显示为绿色，如果不满足显示为红色
	 * @param <array> $setting 传入admincp_save方法中保存的信息
	 * @return <string>返回要显示的内容
	 */
	function memcp_show($setting){}
	
	/**
	 * 检验用户是否满足领取要求
	 * @param <array> $setting 传入admincp_save方法中保存的信息
	 * @return <bool>返回检验是否通过
	 */
	function memcp_check($setting){}
	
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