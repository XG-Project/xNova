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

if ($user['authlevel'] < 1) die(message ($lang['404_page']));

$parse			=	$lang;

$onMouseOverIE		=	"onMouseOver=\"this.className='ForIEHover'\" onMouseOut=\"this.className='ForIE'\"";
$onMouseOverIELime	=	"onMouseOver=\"this.className='ForIEHoverLime'\" onMouseOut=\"this.className='ForIEHoverr'\"";


$ConfigTable	=
		"<table width=\"150\" class=\"s\">
    	<tr>
        	<td colspan=\"2\" class=\"t\">".$lang['mu_general']."</td>
    	</tr>
    	<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SettingsPage.php\" target=\"Hauptframe\">".$lang['mu_settings']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"DataBaseViewPage.php\" target=\"Hauptframe\">".$lang['mu_optimize_db']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"ErrorPage.php\" target=\"Hauptframe\">".$lang['mu_error_list']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"LogToolPage.php\" target=\"Hauptframe\">".$lang['mu_user_logs']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"ConfigStatsPage.php\" target=\"Hauptframe\">".$lang['mu_stats_options']."</a></th>
    	</tr>
		</table>";


$EditTable	=
		"<table width=\"150\" class=\"s\">
    	<tr>
        	<td colspan=\"2\" class=\"t\">".$lang['mu_users_settings']."</td>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"MakerPage.php\" target=\"Hauptframe\">".$lang['new_creator_title']."</a></th>
   	 	</tr>
    	<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"AccountEditorPage.php\" target=\"Hauptframe\">".$lang['mu_add_delete_resources']."</a></th>
   	 	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"BanPage.php\" target=\"Hauptframe\">".$lang['mu_ban_options']."</a></th>
    	</tr>
		</table>";



$ViewTable	=
		"<table width=\"150\" class=\"s\">
    	<tr>
        	<td colspan=\"2\" class=\"t\">".$lang['mu_observation']."</td>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php?search=online&minimize=on\" target=\"Hauptframe\">".$lang['mu_connected']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php?search=p_connect&minimize=on\" target=\"Hauptframe\">".$lang['mu_active_planets']."</a></th>
    	</tr>
    	<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"ShowFlyingFleets.php\" target=\"Hauptframe\">".$lang['mu_flying_fleets']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php?search=users&minimize=on\" target=\"Hauptframe\">".$lang['mu_user_list']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php?search=planet&minimize=on\" target=\"Hauptframe\">".$lang['mu_planet_list']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php?search=moon&minimize=on\" target=\"Hauptframe\">".$lang['mu_moon_list']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"MessageListPage.php\" target=\"Hauptframe\">".$lang['mu_mess_list']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"AccountDataPage.php\" target=\"Hauptframe\">".$lang['mu_info_account_page']."</a></th>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"SearchingPage.php\" target=\"Hauptframe\">".$lang['mu_search_page']."</a></th>
    	</tr>
		</table>";


$ToolsTable	=
		"<table width=\"150\" class=\"s\">
    	<tr>
        	<td colspan=\"2\" class=\"t\">".$lang['mu_tools']."</td>
    	</tr>
		<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"GlobalMessagePage.php\" target=\"Hauptframe\">".$lang['mu_global_message']."</a></th>
    	</tr>
    	<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"PassEncripterPage.php\" target=\"Hauptframe\">".$lang['mu_md5_encripter']."</a></th>
    	</tr>
    	<tr>
        	<th ".$onMouseOverIE." class=\"ForIE\"><a href=\"statbuilder.php\" target=\"Hauptframe\" onClick=\" return confirm('".$lang['mu_mpu_confirmation']."');\">
			".$lang['mu_manual_points_update']."</a></th>
    	</tr>
		</table>";


// MODERADORES
if($user['authlevel'] == 1)
{
	if($Observation == 1) $parse['ViewTable']	=	$ViewTable;
	if($EditUsers 	== 1) $parse['EditTable']	=	$EditTable;
	if($ConfigGame 	== 1) $parse['ConfigTable']	=	$ConfigTable;
	if($ToolsCanUse == 1) $parse['ToolsTable']	=	$ToolsTable;
}

// OPERADORES
if($user['authlevel'] == 2)
{
	if($Observation == 1) $parse['ViewTable']	=	$ViewTable;
	if($EditUsers 	== 1) $parse['EditTable']	=	$EditTable;
	if($ConfigGame 	== 1) $parse['ConfigTable']	=	$ConfigTable;
	if($ToolsCanUse == 1) $parse['ToolsTable']	=	$ToolsTable;
}

//ADMINISTRADORES
if($user['authlevel'] == 3)
{
	$parse['ViewTable']		=	$ViewTable;
	$parse['EditTable']		=	$EditTable;
	$parse['ConfigTable']	=	$ConfigTable;
	$parse['ToolsTable']	=	$ToolsTable;
}



display( parsetemplate(gettemplate('adm/menu'), $parse), FALSE, '', TRUE, FALSE);
?>