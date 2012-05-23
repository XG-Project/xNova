<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  ,    TRUE);
define('INSTALL' ,   FALSE);
define('IN_ADMIN',    TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include(XGP_ROOT . 'includes/functions/DeleteSelectedUser.php');
include('AdminFunctions/Autorization.php');

if ($Observation != 1) die();

	$parse	= $lang;

	if ($_GET['cmd'] == 'dele')
		DeleteSelectedUser ($_GET['user']);
	if ($_GET['cmd'] == 'sort')
		$TypeSort = $_GET['type'];
	else
		$TypeSort = "id";

	$query   = doquery("SELECT `id`,`username`,`email`,`ip_at_reg`,`user_lastip`,`register_time`,`onlinetime`,`bana`,`banaday` FROM {{table}} ORDER BY `". $TypeSort ."` ASC", 'users');

	$parse['adm_ul_table'] = "";
	$i                     = 0;
	$Color                 = "lime";
	while ($u = mysql_fetch_assoc($query))
	{
		if ($PrevIP != "")
		{
			if ($PrevIP == $u['user_lastip'])
				$Color = "red";
			else
				$Color = "lime";
		}

		$Bloc['adm_ul_data_id']     		= $u['id'];
		$Bloc['adm_ul_data_name']   		= $u['username'];
		$Bloc['adm_ul_data_mail']   		= $u['email'];
		$Bloc['ip_adress_at_register']   	= $u['ip_at_reg'];
		$Bloc['adm_ul_data_adip']   		= "<font color=\"".$Color."\">". $u['user_lastip'] ."</font>";
		$Bloc['adm_ul_data_regd']   		= gmdate ( "d/m/Y G:i:s", $u['register_time'] );
		$Bloc['adm_ul_data_lconn']  		= gmdate ( "d/m/Y G:i:s", $u['onlinetime'] );
		$Bloc['adm_ul_data_banna']  		= ($u['bana'] == 1) ? "<a href # title=\"". gmdate ( "d/m/Y G:i:s", $u['banaday']) ."\">".$lang['ul_yes']."</a>" : $lang['ul_no'];
		$Bloc['adm_ul_data_actio']  		= ($u['id'] != $user['id'] && $user['authlevel'] == 3) ? "<a href=\"UserListPage.php?cmd=dele&user=".$u['id']."\" border=\"0\" onclick=\"return confirm('".$lang['ul_sure_you_want_dlte']."  $u[username]?');\"><img border=\"0\" src=\"../styles/images/r1.png\"></a>" : "-";
		$PrevIP                     		= $u['user_lastip'];
		$parse['adm_ul_table']     			.= parsetemplate(gettemplate('adm/UserListRows'), $Bloc);
		$i++;

	}
	$parse['adm_ul_count'] 					= $i;

	display( parsetemplate( gettemplate('adm/UserListBody'), $parse ), FALSE, '', TRUE, FALSE);

?>