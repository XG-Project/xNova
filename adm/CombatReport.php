<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');

includeLang('INGAME');

$raportrow 	= doquery("SELECT * FROM {{table}} WHERE `rid` = '".(mysql_escape_string($_GET["raport"]))."';", 'rw', TRUE);
if (($raportrow["owners"] == $user["id"]) && ($raportrow["a_zestrzelona"] == 1))
{
	$Page .= "<td>".$lang['cr_lost_contact']."<br>";
	$Page .= $lang['cr_first_round']."</td>";
}
else
{
	$report = stripslashes($raportrow["raport"]);
	foreach ($lang['tech_rc'] as $id => $s_name)
	{
		$str_replace1  	= array("[ship[".$id."]]");
		$str_replace2  	= array($s_name);
		$report 		= str_replace($str_replace1, $str_replace2, $report);
	}
	$no_fleet 		= "<table border=1 align=\"center\"><tr><th>".$lang['cr_type']."</th></tr><tr><th>".$lang['cr_total']."</th></tr><tr><th>".$lang['cr_weapons']."</th></tr><tr><th>".$lang['cr_shields']."</th></tr><tr><th>".$lang['cr_armor']."</th></tr></table>";
	$destroyed 		= "<table border=1 align=\"center\"><tr><th><font color=\"red\"><strong>".$lang['cr_destroyed']."</strong></font></th></tr></table>";
	$str_replace1  	= array($no_fleet);
	$str_replace2  	= array($destroyed);
	$report 		= str_replace($str_replace1, $str_replace2, $report);
	$Page 		   .= $report;
}

display($Page, FALSE, '', FALSE, FALSE);
?>