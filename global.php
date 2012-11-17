<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if (version_compare(PHP_VERSION, "5.3.0", "<"))
	die('<!DOCTYPE html><html><head><title>¡Error!</title><meta charset="UTF-8"></head><body>¡Error! Tu servidor debe tener al menos php 5.3.0</body></html>');

// -1 permitirá guardar todos los errores
error_reporting(-1);

$user			= array();
$lang			= array();
$IsUserChecked	= FALSE;

// CONEXIÓN CON LA BASE DE DATOS \\
require_once(XN_ROOT.'config.php');
if (isset($dbsettings))
{
	$db			= new mysqli($dbsettings["server"], $dbsettings["user"], $dbsettings["pass"], $dbsettings["name"]);
	if ( ! is_null($db->connect_error)) $debug->error($db->connect_error, "SQL Error");

	$db->set_charset('utf8');
	unset($dbsettings);
}
else
{
	$db			= NULL;
}
// CONEXIÓN CON LA BASE DE DATOS \\

if ( ! is_null($db))
{
	require_once('includes/classes/class.SecurePage.php');
	SecurePage::run();
}

include_once(XN_ROOT.'includes/constants.php');
include_once(XN_ROOT.'includes/GeneralFunctions.php');
include_once(XN_ROOT.'includes/classes/class.debug.php');
include_once(XN_ROOT.'includes/classes/class.xml.php');
include_once(XN_ROOT.'includes/classes/class.Format.php');
include_once(XN_ROOT.'includes/classes/class.NoobsProtection.php');
include_once(XN_ROOT.'includes/classes/class.Production.php');
include_once(XN_ROOT.'includes/classes/class.Fleets.php');

$debug			= new debug();

if (filesize(XN_ROOT.'config.php') === 0 && (( !  defined('INSTALL')) OR ( ! INSTALL)))
{
	exit(header('location: '.GAMEURL.'install.php'));
}

if (filesize(XN_ROOT.'config.php') !== 0)
{
	$game_version	=	read_config('version');
	define('VERSION', empty($game_version) ? '' : 'v'.$game_version);
}

if ( ! defined('INSTALL') OR ( ! INSTALL))
{
	include_once(XN_ROOT.'includes/vars.php');
	include_once(XN_ROOT.'includes/functions/CreateOneMoonRecord.php');
	include_once(XN_ROOT.'includes/functions/CreateOnePlanetRecord.php');
	include_once(XN_ROOT.'includes/functions/SendSimpleMessage.php');
	include_once(XN_ROOT.'includes/functions/calculateAttack.php');
	include_once(XN_ROOT.'includes/functions/formatCR.php');
	include_once(XN_ROOT.'includes/functions/GetBuildingTime.php');
	include_once(XN_ROOT.'includes/functions/HandleElementBuildingQueue.php');
	include_once(XN_ROOT.'includes/functions/PlanetResourceUpdate.php');

	$game_lang	=	read_config('lang');

	define('DEFAULT_LANG', ($game_lang  == '') ? "spanish" : $game_lang);

	includeLang('INGAME');

	if (read_config('bots') > 0 && read_config('bots_last_update') < time()-60)
	{
		include_once(XN_ROOT.'includes/classes/class.Bot.php');
		UpdateBots();
	}

	include_once(XN_ROOT.'includes/classes/class.CheckSession.php');

	$Result        	= new CheckSession();
	$Result			= $Result->CheckUser($IsUserChecked);
	$IsUserChecked 	= $Result['state'];

	if (isset($InLogin) && $InLogin && $IsUserChecked)
	{
		header('Location: '.GAMEURL.'game.php?page=overview');
	}
	elseif (( !  isset($InLogin) OR ( ! $InLogin)) && ( ! $IsUserChecked))
	{
		header('Location: '.GAMEURL);
	}
	$user          	= $Result['record'];

	if (defined('IN_ADMIN'))
		define('DPATH', GAMEURL.DEFAULT_SKINPATH);
	else
		define('DPATH', (( !  isset($user["dpath"]) OR (empty($user["dpath"]))) ? GAMEURL.DEFAULT_SKINPATH : GAMEURL.SKIN_PATH.$user["dpath"].'/'));

	define('AUTHLEVEL', (isset($user['authlevel']) ? (int) $user['authlevel'] : 0));

	if (read_config('game_disable') === 0 && AUTHLEVEL === 0)
	{
		message(stripslashes(read_config('close_reason')), '', '', FALSE, FALSE);
	}

	if ((time() >= (read_config('stat_last_update') + (60 * read_config('stat_update_time')))))
	{
		require_once(XN_ROOT.'includes/functions/adm/statfunctions.php');
		$result	= MakeStats();
		update_config('stat_last_update', $result['stats_time']);
	}

	if ( ! empty($user))
	{
		include_once(XN_ROOT.'includes/classes/class.FlyingFleetHandler.php');

		$_fleets = doquery("SELECT fleet_start_galaxy,fleet_start_system,fleet_start_planet,fleet_start_type FROM `{{table}}` WHERE `fleet_start_time` <= '".time()."' and `fleet_mess` ='0' order by fleet_id asc;", 'fleets'); // OR fleet_end_time <= ".time()

		while ($row = $_fleets->fetch_assoc())
		{
			$array 					= array();
			$array['galaxy'] 		= $row['fleet_start_galaxy'];
			$array['system'] 		= $row['fleet_start_system'];
			$array['planet'] 		= $row['fleet_start_planet'];
			$array['planet_type'] 	= $row['fleet_start_type'];

			$temp = new FlyingFleetHandler($array);
		}

		$_fleets->free_result();

		$_fleets = doquery("SELECT fleet_end_galaxy,fleet_end_system,fleet_end_planet ,fleet_end_type FROM `{{table}}` WHERE `fleet_end_time` <= '". time()." order by fleet_id asc';", 'fleets'); // OR fleet_end_time <= ".time()

		while ($row = $_fleets->fetch_assoc())
		{
			$array 					= array();
			$array['galaxy'] 		= $row['fleet_end_galaxy'];
			$array['system'] 		= $row['fleet_end_system'];
			$array['planet'] 		= $row['fleet_end_planet'];
			$array['planet_type'] 	= $row['fleet_end_type'];

			$temp = new FlyingFleetHandler($array);
		}

		$_fleets->free_result();
		unset($_fleets);

		if (defined('IN_ADMIN'))
		{
			includeLang('ADMIN');
			require_once(XN_ROOT.'includes/functions/adm/Autorization.php');
		}

		if (isset($user['current_planet']))
		{
			require_once(XN_ROOT.'includes/functions/SetSelectedPlanet.php');
			SetSelectedPlanet($user);

			$planetrow = doquery("SELECT * FROM `{{table}}` WHERE `id` = '".$user['current_planet']."';", "planets", TRUE);
		}
		// Include the plugin system
		include(XN_ROOT.'includes/plugins.php');
	}
}


/* End of file global.php */
/* Location: ./global.php */