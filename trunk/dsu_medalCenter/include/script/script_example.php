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
	var $introduction = '��ֻ��һ���������߲ο��ķ����ű�����ʵ�����壬����װ����ʽվ�㡣'; //������������д�������չ�Ľ���
	
	
	
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
		return '';
	}
	
	/**
	 * �������ύ������ݽ��кϷ��Լ���
	 */
	function admincp_check(){
		return true;
	}
	
	/**
	 * @return <array>����Ҫ���������
	 */
	function admincp_save(){
		return array();
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
		return '';
	}
	
	/**
	 * �����û��Ƿ�������ȡҪ��
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 * @return <bool>���ؼ����Ƿ�ͨ��
	 */
	function memcp_check($setting){
		return true;
	}
	
	/**
	 * ���û���ȡ�ɹ���ǰ̨ѫ�����ģ��󣬻��Զ����ô˷���
	 * @param <array> $setting ����admincp_save�����б������Ϣ
	 */
	function memcp_get_succeed($setting){
		return;
	}
	
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