<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_example{
	
	var $name = '��������'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	//var $introduction = '������������д�������չ�Ľ���';
	
	
	
	/**
	 * ��ʾ����ʱ������
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 */
	function admincp_show($setting){}
	
	/**
	 * �������ύ������ݽ��кϷ��Լ���
	 */
	function admincp_check(){}
	
	/**
	 * @return <array>����Ҫ���������
	 */
	function admincp_save(){
		return array();
	}
	
	/**
	 * @return <bool>���ؼ����Ƿ�ͨ��
	 */
	function memcp_check(){}
	
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