<?php
/*
	dsu_medalCenter (C)2010 Discuz Student Union
	This is NOT a freeware, use is subject to license terms

	$Id$
*/
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');

loadcache('plugin');
$cvars = &$_G['cache']['plugin']['dsu_medalCenter'];
require_once DISCUZ_ROOT.'./source/plugin/dsu_medalCenter/include/function_common.php';

if(empty($_G['gp_pdo']) || $_G['gp_pdo'] == 'list'){ //�б�ҳ��
	if(!submitcheck('medalsubmit')) {
		showtips('medals_tips');
		showformheader('plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage');
		showtableheader('medals_list', 'fixpadding');
		showsubtitle(array('', 'display_order', 'available', 'name', 'description', 'medals_image', 'medals_type', ''));

?>
<script type="text/JavaScript">
	var rowtypedata = [
		[
			[1,'', 'td25'],
			[1,'<input type="text" class="txt" name="newdisplayorder[]" size="3">', 'td28'],
			[1,'', 'td25'],
			[1,'<input type="text" class="txt" name="newname[]" size="10">'],
			[1,'<input type="text" class="txt" name="newdescription[]" size="30">'],
			[1,'<input type="text" class="txt" name="newimage[]" size="20">'],
			[1,'', 'td23'],
			[1,'', 'td25']
		]
	];
</script>
<?
		$query = DB::query("SELECT * FROM ".DB::table('forum_medal')." ORDER BY displayorder");
		while($medal = DB::fetch($query)) {
			$checkavailable = $medal['available'] ? 'checked' : '';
			switch($medal['type']) {
				case 0:
					$medal['type'] = cplang('medals_adminadd');
					break;
				case 1:
					$medal['type'] = cplang('medals_register');
					break;
				case 2:
					$medal['type'] = cplang('modals_moderate');
					break;
			}
			showtablerow('', array('class="td25"', 'class="td25"', 'class="td25"', '', '', '', 'class="td23"', 'class="td25"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$medal[medalid]\">",
				"<input type=\"text\" class=\"txt\" size=\"3\" name=\"displayorder[$medal[medalid]]\" value=\"$medal[displayorder]\">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"available[$medal[medalid]]\" value=\"1\" $checkavailable>",
				"<input type=\"text\" class=\"txt\" size=\"10\" name=\"name[$medal[medalid]]\" value=\"$medal[name]\">",
				"<input type=\"text\" class=\"txt\" size=\"30\" name=\"description[$medal[medalid]]\" value=\"$medal[description]\">",
				"<input type=\"text\" class=\"txt\" size=\"20\" name=\"image[$medal[medalid]]\" value=\"$medal[image]\"><img style=\"vertical-align:middle\" src=\"static/image/common/$medal[image]\">",
				$medal[type],
				"<a href=\"".ADMINSCRIPT."?action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid=$medal[medalid]\" class=\"act\">$lang[detail]</a>"
			));
		}

		echo '<tr><td></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['medals_addnew'].'</a></div></td></tr>';
		showsubmit('medalsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		if(is_array($_G['gp_delete'])) {
			$ids = $comma = '';
			foreach($_G['gp_delete'] as $id) {
				$ids .= "$comma'$id'";
				$comma = ',';
			}
			DB::query("DELETE FROM ".DB::table('forum_medal')." WHERE medalid IN ($ids)");
			DB::query("DELETE FROM ".DB::table('dsu_medalfield')." WHERE medalid IN ($ids)");
		}

		if(is_array($_G['gp_name'])) {
			foreach($_G['gp_name'] as $id => $val) {
				DB::query("UPDATE ".DB::table('forum_medal')." SET name=".($_G['gp_name'][$id] ? '\''.dhtmlspecialchars($_G['gp_name'][$id]).'\'' : 'name').", available='{$_G['gp_available'][$id]}', description=".($_G['gp_description'][$id] ? '\''.dhtmlspecialchars($_G['gp_description'][$id]).'\'' : 'name').", displayorder='".intval($_G['gp_displayorder'][$id])."', image=".($_G['gp_image'][$id] ? '\''.$_G['gp_image'][$id].'\'' : 'image')." WHERE medalid='$id'");
			}
		}

		if(is_array($_G['gp_newname'])) {
			foreach($_G['gp_newname'] as $key => $value) {
				if($value != '' && $_G['gp_newimage'][$key] != '') {
					$data = array('name' => dhtmlspecialchars($value),
						'available' => $_G['gp_newavailable'][$key],
						'image' => $_G['gp_newimage'][$key],
						'displayorder' => intval($_G['gp_newdisplayorder'][$key]),
						'description' => dhtmlspecialchars($_G['gp_newdescription'][$key]),
					);
					DB::insert('forum_medal', $data);
				}
			}
		}

		updatecache('setting');
		updatecache('medals');
		cpmsg('medals_succeed', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage', 'succeed');
	}
}elseif($_G['gp_pdo'] == 'edit'){ //ѫ�±༭ҳ��
	
	$medalid = intval($_G['gp_medalid']);

	if(!submitcheck('medaleditsubmit')) {

		$medal = DB::fetch_first("SELECT m.*, mf.* FROM ".DB::table('forum_medal')." m LEFT JOIN ".DB::table('dsu_medalfield')." mf ON m.medalid = mf.medalid WHERE m.medalid='$medalid'");

		$checkmedaltype = array($medal['type'] => 'checked');
		$query = DB::query("SELECT typeid, name FROM ".DB::table('dsu_medaltype'));
		$typevar = array('typeidnew', array());
		$typevar[1][] = array(0, 'δ����');
		while($tinfo = DB::fetch($query)){
			$typevar[1][] = array($tinfo['typeid'], $tinfo['name']);
		}
		showformheader("plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage&pdo=edit&medalid=$medalid");
		showtableheader(cplang('medals_edit').' - '.$medal['name'], 'nobottom');
		showsetting('medals_name1', 'namenew', $medal['name'], 'text');
		showsetting('medals_img', '', '', '<input type="text" class="txt" size="30" name="imagenew" value="'.$medal['image'].'" ><img src="static/image/common/'.$medal['image'].'">');
		showsetting('medals_type1', '', '', '<ul class="nofloat" onmouseover="altStyle(this);">
			<li'.($checkmedaltype[0] ? ' class="checked"' : '').'><input name="typenew" type="radio" class="radio" value="0" '.$checkmedaltype[0].'>&nbsp;'.$lang['medals_adminadd'].'</li>
			<li'.($checkmedaltype[1] ? ' class="checked"' : '').'><input name="typenew" type="radio" class="radio" value="1" '.$checkmedaltype[1].'>&nbsp;'.$lang['medals_apply_auto'].'</li>
			<li'.($checkmedaltype[2] ? ' class="checked"' : '').'><input name="typenew" type="radio" class="radio" value="2" '.$checkmedaltype[2].'>&nbsp;'.$lang['medals_apply_noauto'].'</li></ul>'
		);
		showsetting('medals_expr1', 'expirationnew', $medal['expiration'], 'number');
		showsetting('ѫ�·���', $typevar, $medal['typeid'], 'select');
		showsetting('medals_memo', 'descriptionnew', $medal['description'], 'text');
		showtablefooter();
		$medalfieldSetting = (array)unserialize($medal['setting']);
		foreach(getMedalExtendClass() as $classname => $newclass){ //��չ����ʾ����ҳ��
			if(method_exists($newclass, 'admincp_show')) $newclass->admincp_show($medalfieldSetting[$classname]);
		}
		showtableheader('', 'notop');
		showsubmit('medaleditsubmit');
		showtablefooter();
		showformfooter();
	} else {
		foreach(getMedalExtendClass() as $classname => $newclass){ //��չ������ύ��Ϣ
			if(method_exists($newclass, 'admincp_check')) $newclass->admincp_check();
		}

		$medalfieldSetting = array(); //��չ�������ύ��Ϣ
		foreach(getMedalExtendClass() as $classname => $newclass){
			if(method_exists($newclass, 'admincp_save')) $medalfieldSetting[$classname] = $newclass->admincp_save();
		}
		$medalfieldSetting = addslashes(serialize($medalfieldSetting));
		DB::insert('dsu_medalfield',array(
			'medalid' => $medalid,
			'typeid' => intval($_G['gp_typeidnew']),
			'setting' => $medalfieldSetting,
		), false, true);
		
		DB::update('forum_medal', array(
			'name' => $_G['gp_namenew'] ? dhtmlspecialchars($_G['gp_namenew']) : 'name',
			'type' => $_G['gp_typenew'],
			'description' => dhtmlspecialchars($_G['gp_descriptionnew']),
			'expiration' => intval($_G['gp_expirationnew']),
			'permission' => $formulapermnew,
			'image' => $_G['gp_imagenew'],
		), "medalid='$medalid'");

		updatecache('medals');
		cpmsg('medals_succeed', 'action=plugins&operation=config&identifier=dsu_medalCenter&pmod=admin_manage', 'succeed');
	}

}
?>