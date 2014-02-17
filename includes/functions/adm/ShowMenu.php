<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

function ShowMenu ()
{
	global $lang;

	$parse = $lang;

	if (ADM_OBSERVATION)	$parse['ViewTable']			=	parsetemplate(gettemplate('adm/menu/menu_view'), $lang);
	if (ADM_USER_EDIT)		$parse['EditTable']			=	parsetemplate(gettemplate('adm/menu/menu_edit'), $lang);
	if (ADM_CONFIGURATION)	$parse['ConfigTable']		=	parsetemplate(gettemplate('adm/menu/menu_config'), $lang);
	if (ADM_TOOLS)			$parse['ToolsTable']		=	parsetemplate(gettemplate('adm/menu/menu_tools'), $lang);

	if (ADM_OBSERVATION)	$parse['View_select']		=	parsetemplate(gettemplate('adm/menu/view_select'), $lang);
	if (ADM_USER_EDIT)		$parse['Edit_select']		=	parsetemplate(gettemplate('adm/menu/edit_select'), $lang);
	if (ADM_CONFIGURATION)	$parse['Config_select']		=	parsetemplate(gettemplate('adm/menu/config_select'), $lang);
	if (ADM_TOOLS)			$parse['Tools_select']		=	parsetemplate(gettemplate('adm/menu/tools_select'), $lang);

	if (AUTHLEVEL === 3) $parse['topnav']	=	parsetemplate(gettemplate('adm/menu/topnav_select'), $lang);

	return parsetemplate(gettemplate('adm/menu/menu_body'), $parse);
}


/* End of file ShowMenu.php */
/* Location: ./includes/functions/adm/ShowMenu.php */