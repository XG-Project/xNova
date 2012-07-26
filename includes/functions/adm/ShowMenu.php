<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

function ShowMenu ()
{
	global $lang, $user, $Observation, $EditUsers, $ConfigGame, $ToolsCanUse;

	$parse = $lang;

	if ($Observation)	$parse['ViewTable']			=	parsetemplate(gettemplate('adm/menu/menu_view'), $lang);
	if ($EditUsers)		$parse['EditTable']			=	parsetemplate(gettemplate('adm/menu/menu_edit'), $lang);
	if ($ConfigGame)	$parse['ConfigTable']		=	parsetemplate(gettemplate('adm/menu/menu_config'), $lang);
	if ($ToolsCanUse)	$parse['ToolsTable']		=	parsetemplate(gettemplate('adm/menu/menu_tools'), $lang);

	if ($Observation)	$parse['View_select']		=	parsetemplate(gettemplate('adm/menu/view_select'), $lang);
	if ($EditUsers)		$parse['Edit_select']		=	parsetemplate(gettemplate('adm/menu/edit_select'), $lang);
	if ($ConfigGame)	$parse['Config_select']		=	parsetemplate(gettemplate('adm/menu/config_select'), $lang);
	if ($ToolsCanUse)	$parse['Tools_select']		=	parsetemplate(gettemplate('adm/menu/tools_select'), $lang);

	if ($user['authlevel'] == 3) $parse['topnav']	=	parsetemplate(gettemplate('adm/menu/topnav_select'), $lang);

	return parsetemplate(gettemplate('adm/menu/menu_body'), $parse);
}


/* End of file ShowMenu.php */
/* Location: ./includes/functions/adm/ShowMenu.php */