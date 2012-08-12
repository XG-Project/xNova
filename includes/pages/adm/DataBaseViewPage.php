<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

define('INSIDE' , TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT.'global.php');

if ($ConfigGame != 1) die(message($lang['404_page']));

$parse = $lang;

if ( ! $_POST)
{
	$Tablas = doquery("SHOW TABLES","todas");
	while ($row = $Tablas->fetch_assoc())
	{
		foreach ($row as $opcion => $tabla)
		{
			$parse['tabla'] .= "<tr>";
			$parse['tabla'] .= "<th width=\"50%\">".$tabla."</th><th width=\"50%\"><font color=aqua>".$lang['od_select_action']."</font></th>";
			$parse['tabla'] .= "</tr>";
		}
	}
}
else
{
	$Tablas = doquery("SHOW TABLES",'todas');

	while ($row = $Tablas->fetch_assoc())
	{
		foreach ($row as $opcion => $tabla)
		{
			if ($_POST['Optimize']){
				doquery("OPTIMIZE TABLE {$tabla}", "$tabla");
				$Message	=	$lang['od_opt'];
				$Log	=	"\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_optimize']."\n";}

			if ($_POST['Repair']){
				doquery("REPAIR TABLE {$tabla}", "$tabla");
				$Message	=	$lang['od_rep'];
				$Log	=	"\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_repair']."\n";}

			if ($_POST['Check']){
				doquery("CHECK TABLE {$tabla}", "$tabla");
				$Message	=	$lang['od_check_ok'];
				$Log	=	"\n".$lang['log_database_title']."\n".$lang['log_the_user'].$user['username'].$lang['log_database_view'].":\n".$lang['log_data_check']."\n";}

			if ($db->errno)
			{
				$parse['tabla'] .= "<tr>";
				$parse['tabla'] .= "<th width=\"50%\">".$tabla."</th>";
				$parse['tabla'] .= "<th width=\"50%\" style=\"color:red\">".$lang['od_not_opt']."</th>";
				$parse['tabla'] .= "</tr>";
			}
			else
			{
				$parse['tabla'] .= "<tr>";
				$parse['tabla'] .= "<th width=\"50%\">".$tabla."</th>";
				$parse['tabla'] .= "<th width=\"50%\" style=\"color:lime\">".$Message."</th>";
				$parse['tabla'] .= "</tr>";
			}
		}
	}

	LogFunction($Log, "GeneralLog");
}

display(parsetemplate(gettemplate('adm/DataBaseViewBody'), $parse), FALSE, '', TRUE, FALSE);


/* End of file DatabaseViewPage.php */
/* Location: ./adm/DatabaseViewPage.php */