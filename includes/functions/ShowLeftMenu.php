<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

function ShowLeftMenu ()
{
	global $lang, $user;

	$parse					= $lang;
	$parse['dpath']			= DPATH;
	$parse['version']   	= VERSION;
	$parse['servername']	= read_config ( 'game_name' );
	$parse['forum_url']     = read_config ( 'forum_url' );
	$parse['user_rank']     = $user['total_rank'];

	if ($user['authlevel'] > 0)
	{
		$parse['admin_link']	="<tr><td><div align=\"center\"><a href=\"adm/index.php\" target=\"_top\"> <font color=\"lime\">" . $lang['lm_administration'] . "</font></a></div></td></tr>";
	}
	else
	{
		$parse['admin_link']  	= "";
	}

	return parsetemplate(gettemplate('general/left_menu'), $parse);
}
?>