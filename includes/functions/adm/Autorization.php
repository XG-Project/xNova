<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

require_once(XN_ROOT.'includes/functions/adm/LogFunction.php');

if ( $user['authlevel'] < 1 )
{
	die();
}

$QueryModeration	=	read_config('moderation');
$QueryModerationEx  =   explode(";", $QueryModeration);
$Moderator			=	explode(",", $QueryModerationEx[0]);
$Operator			=	explode(",", $QueryModerationEx[1]);
$Administrator		=	explode(",", $QueryModerationEx[2]);

if ($user['authlevel'] == 1)
{
	$Observation	=	$Moderator[0];
	$EditUsers		=	$Moderator[1];
	$ConfigGame		=	$Moderator[2];
	$ToolsCanUse	=	$Moderator[3];
	$LogCanWork		=	$Moderator[4];
}

if ($user['authlevel'] == 2)
{
	$Observation	=	$Operator[0];
	$EditUsers		=	$Operator[1];
	$ConfigGame		=	$Operator[2];
	$ToolsCanUse	=	$Operator[3];
	$LogCanWork		=	$Operator[4];
}

if ($user['authlevel'] == 3)
{
	$Observation	=	1;
	$EditUsers		=	1;
	$ConfigGame		=	1;
	$ToolsCanUse	=	1;
	$LogCanWork		=	$Administrator[0];
}


/* End of file Autorization.php */
/* Location: ./includes/functions/adm/Autorization.php */