<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE', TRUE);
define('INSTALL', FALSE);
define('IN_ADMIN', TRUE);
define('XN_ROOT', './../');

include(XN_ROOT.'global.php');

if ($user['authlevel'] < 1) die(message ($lang['404_page']));

function check_updates()
{
	if (function_exists('file_get_contents'))
	{
		$last_v 	= @file_get_contents('http://xnova.razican.com/current.php');
		$system_v	= read_config('version');

		return version_compare($system_v, $last_v, '<');
	}
}

$parse	=	$lang;

if (file_exists(XN_ROOT.'install/') && defined('IN_ADMIN'))
{
	$Message	.= "<font color=\"red\">".$lang['ow_install_file_detected']."</font><br><br>";
	$error++;
}

if ($user['authlevel'] >= 3)
{
	if (is_writable(XN_ROOT.'config.php'))
	{
		$Message	.= "<font color=\"red\">".$lang['ow_config_file_writable']."</font><br><br>";
		$error++;
	}

	if ( ! file_exists(XN_ROOT.'includes/bots') OR ! is_writable(XN_ROOT.'includes/bots'))
	{
		$Message	.= "<font color=\"red\">".$lang['ow_bot_folder_error']."</font><br><br>";
		$error++;
	}

	if ( ! is_writable(XN_ROOT.'includes/xml/config.xml'))
	{
		$Message	.= "<font color=\"red\">".$lang['ow_config_file_no_writable']."</font><br><br>";
		$error++;
	}

	if ( ! file_exists(XN_ROOT.'includes/plugins'))
	{
		$Message	.= "<font color=\"red\">".$lang['ow_plugins_dir_not_exists']."</font><br><br>";
		$error++;
	}

	foreach (scandir(XN_ROOT.'adm/Log') as $log_file)
	{
		if ($log_file != '.htaccess' && $log_file != 'index.html' && is_file(XN_ROOT.'adm/Log/'.$log_file) && ( ! is_writable(XN_ROOT.'adm/Log/'.$log_file)))
		{
			$Message	.= "<font color=\"red\">".$lang['ow_log_file_no_writable']."</font><br><br>";
			$error++;
			break;
		}
	}

	$Errors = doquery("SELECT COUNT(*) AS `errors` FROM {{table}} WHERE 1;", 'errors', TRUE);

	if ($Errors['errors'] != 0)
	{
		$Message	.= "<font color=\"red\">".$lang['ow_database_errors']."</font><br><br>";
		$error++;
	}

	if (check_updates())
	{
		$Message	.= "<font color=\"red\">".$lang['ow_old_version']."</font><br><br>";
		$error++;
	}
}

if ($error != 0)
{
	$parse['error_message']		=	$Message;
	$parse['color']				=	"red";}
else
{
	$parse['error_message']		= 	$lang['ow_none'];
	$parse['color']				=	"lime";
}


display( parsetemplate(gettemplate('adm/OverviewBody'), $parse), FALSE, '', TRUE, FALSE);
?>