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
	 * @return <mixed>���ؼ����Ƿ�ͨ��
	 *	����TRUE�������ɹ�
	 *	����FALSE�������ʧ�ܣ�Ĭ�ϣ�������ֵ��TRUEʱ��Ϊ����ʧ�ܣ�
	 *	��Ҫͬʱ�Զ��尲װ����ʾ����Ϣ���뷵��һ�����飬��ʽΪarray(��װ�Ƿ�ɹ�, ��ʾ��Ϣ)�� e.g. return array(false, '���Ȱ�װXX������ٰ�װ����չ');
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
	 * @return <mixed>
	 *	����FALSE����װʧ��
	 *	����TRUE����װ�ɹ���Ĭ�ϣ�������ֵ�ǲ����ͻ�����ʱ�����޷���ֵʱ��Ϊ��װ�ɹ���
	 *	��Ҫͬʱ�Զ��尲װ����ʾ����Ϣ���뷵��һ�����飬��ʽΪarray(��װ�Ƿ�ɹ�, ��ʾ��Ϣ)�� e.g. return array(false, '���Ȱ�װXX������ٰ�װ����չ');
	 *	ע�⣺���ǵ�������չ�ԣ�����ֵ���趨ΪTRUE/FALSE/NULL(���޷���ֵ�Ƽ���Ҫʵ�ִ˷���)/����
	 */
	function install(){
		return array(false, 'ʾ��������������߲ο���������������������');
	}
	
	/**
	 * ����չ�ű�����ʱ���Զ����ô˷�����
	 * @return <mixed>
	 *	����FALSE��������ʧ��
	 *	����TRUE���������ɹ���Ĭ�ϣ�������ֵ�ǲ����ͻ�����ʱ�����޷���ֵʱ��Ϊ�����ɹ���
	 *	��Ҫͬʱ�Զ�����������ʾ����Ϣ���뷵��һ�����飬��ʽΪarray(�����Ƿ�ɹ�, ��ʾ��Ϣ)�� e.g. return array(false, '��չ�����ײ���汾��ƥ�䣬���ȸ��²��');
	 *	ע�⣺���ǵ�������չ�ԣ�����ֵ���趨ΪTRUE/FALSE/NULL(���޷���ֵ�Ƽ���Ҫʵ�ִ˷���)/����
	 */
	function upgrade(){
		return array(false, 'ʾ��������������߲ο���������������������');
	}
	
	/**
	 * ����չ�ű�ж��ʱ���Զ����ô˷�����
	 * @return <mixed>
	 *	����FALSE����ж��ʧ��
	 *	����TRUE����ж�سɹ���Ĭ�ϣ�������ֵ�ǲ����ͻ�����ʱ�����޷���ֵʱ��Ϊж�سɹ���
	 *	��Ҫͬʱ�Զ���ж�غ���ʾ����Ϣ���뷵��һ�����飬��ʽΪarray(ж���Ƿ�ɹ�, ��ʾ��Ϣ)�� e.g. return array(false, 'ϵͳģ���ֹж��');
	 *	ע�⣺���ǵ�������չ�ԣ�����ֵ���趨ΪTRUE/FALSE/NULL(���޷���ֵ�Ƽ���Ҫʵ�ִ˷���)/����
	 */
	function uninstall(){
		return array(false, 'ʾ��������������߲ο���������������������');
	}
}
?>