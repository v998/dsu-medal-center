<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
class script_base{
	
	var $name = '��������'; //��չ�ű���
	var $version = '1.0'; //��չ�ű��汾��
	var $copyright = '<a href="www.dsu.cc">DSU Team</a>';
	
	
	
	/**
	 * ��ʾ����ʱ������
	 */
	function admincp_show(){}
	
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