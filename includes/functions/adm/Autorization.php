<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

require_once(XN_ROOT.'includes/functions/adm/LogFunction.php');

if (AUTHLEVEL < 1)	die();

$QueryModeration	=	read_config('moderation');
$QueryModerationEx  =   explode(";", $QueryModeration);
$Moderator			=	explode(",", $QueryModerationEx[0]);
$Operator			=	explode(",", $QueryModerationEx[1]);
$Administrator		=	explode(",", $QueryModerationEx[2]);

if (AUTHLEVEL == 1)
{
	define('ADM_OBSERVATION'	, (bool) $Moderator[0]);
	define('ADM_USER_EDIT'		, (bool) $Moderator[1]);
	define('ADM_CONFIGURATION'	, (bool) $Moderator[2]);
	define('ADM_TOOLS'			, (bool) $Moderator[3]);
	define('ADM_LOGS'			, (bool) $Moderator[4]);
}

if (AUTHLEVEL == 2)
{
	define('ADM_OBSERVATION'	, (bool) $Operator[0]);
	define('ADM_USER_EDIT'		, (bool) $Operator[1]);
	define('ADM_CONFIGURATION'	, (bool) $Operator[2]);
	define('ADM_TOOLS'			, (bool) $Operator[3]);
	define('ADM_LOGS'			, (bool) $Operator[4]);
}

if (AUTHLEVEL == 3)
{
	define('ADM_OBSERVATION'	, TRUE);
	define('ADM_USER_EDIT'		, TRUE);
	define('ADM_CONFIGURATION'	, TRUE);
	define('ADM_TOOLS'			, TRUE);
	define('ADM_LOGS'			, (bool) $Administrator[0]);
}


/* End of file Autorization.php */
/* Location: ./includes/functions/adm/Autorization.php */