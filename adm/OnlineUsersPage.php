<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include('AdminFunctions/Autorization.php');

if ($Observation != 1) die();

$parse	= $lang;

if ($_GET['cmd'] == 'sort')
	$TypeSort = $_GET['type'];
else
	$TypeSort = "id";

$queryuser 	= "u.id, u.username, u.user_agent, u.current_page, u.user_lastip, u.ally_name, u.onlinetime, u.email, u.galaxy, u.system, u.planet, u.urlaubs_modus, u.bana";
$querystat 	= "s.total_points";
$Last15Mins = doquery("SELECT ". $queryuser .", ". $querystat ." FROM  {{table}}users as u, {{table}}statpoints as s
							WHERE u.onlinetime >= '". (time() - 15 * 60) ."' AND u.id=s.id_owner AND s.stat_type=1
							ORDER BY `". mysql_escape_string($TypeSort) ."` ASC;", '');


$Count      = 0;
$Color      = "lime";

while ($TheUser = mysql_fetch_array($Last15Mins) )
{
	if ($PrevIP != "")
		if ($PrevIP == $TheUser['user_lastip'])
			$Color = "red";
	else
		$Color = "lime";


	$Bloc['dpath']              = DPATH;
	$Bloc['adm_ov_data_id']     = $TheUser['id'];
	$Bloc['adm_ov_data_name']   = $TheUser['username'];
	$Bloc['adm_ov_data_agen']   = $TheUser['user_agent'];
	$Bloc['current_page']    	= str_replace("%20", " ", $TheUser['current_page']);
	$Bloc['usr_s_id']    		= $TheUser['id'];
	$Bloc['adm_ov_data_clip']   = $Color;
	$Bloc['adm_ov_data_adip']   = $TheUser['user_lastip'];
	$Bloc['adm_ov_data_ally']   = $TheUser['ally_name'];
	$Bloc['adm_ov_data_point']  = Format::pretty_number ( $TheUser['total_points'] );
	$Bloc['adm_ov_data_activ']  = Format::pretty_time ( time() - $TheUser['onlinetime'] );
	$Bloc['adm_ov_data_pict']   = "m.gif";
	$PrevIP                     = $TheUser['user_lastip'];
	$Bloc['usr_email']    		= $TheUser['email'];

	if ($TheUser['urlaubs_modus'] == 1)
		$Bloc['state_vacancy']  = $lang['ou_yes_yes'];
	else
		$Bloc['state_vacancy']  = $lang['ou_not_banned'];

	if ($TheUser['bana'] == 1)
		$Bloc['is_banned']  	= $lang['ou_yes_yes'];
	else
		$Bloc['is_banned']  	= $lang['ou_not_banned'];

	$Bloc['usr_planet_gal']    	= $TheUser['galaxy'];
	$Bloc['usr_planet_sys']    	= $TheUser['system'];
	$Bloc['usr_planet_pos']    	= $TheUser['planet'];
	$parse['adm_ov_data_table'] .= parsetemplate( gettemplate('adm/OnlineUsersRow'), $Bloc );
	$Count++;
}

$parse['adm_ov_data_count']  	= $Count;

display ( parsetemplate(gettemplate('adm/OnlineUsersBody'), $parse), FALSE, '', TRUE, FALSE);

?>