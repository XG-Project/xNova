<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

set_error_handler('catch_error');

function unset_vars($prefix)
{
	$vars = array_keys($GLOBALS);

	for ($n = 0, $i = 0; $i < count($vars); $i ++)
	{
		if (strpos($vars[$i], $prefix) === 0)
		{
			unset($GLOBALS[$vars[$i]]);
			$n ++;
		}
	}

	return  $n;
}

// READS CONFIGURATIONS
function read_config($config_name = '', $all = FALSE)
{
	$configs		= xml::getInstance('config.xml');

	if ($all)
	{
		return $configs->get_configs();
	}
	else
	{
		$value = $configs->get_config($config_name);
		if (is_numeric($value)) $value = (int) $value;

		return $value;
	}
}

// WRITES CONFIGURATIONS
function update_config($config_name, $config_value)
{
	$configs		= xml::getInstance('config.xml');

	$configs->write_config($config_name, $config_value);
}

// DETERMINES IF THE PLAYER IS WEAK
function is_weak($current_points, $other_points)
{
	$weak	= NoobsProtection::getInstance();

	return $weak->is_weak($current_points, $other_points);
}

// DETERMINES IF THE PLAYER IS STRONG
function is_strong($current_points, $other_points)
{
	$strong	= NoobsProtection::getInstance();

	return $strong->is_strong($current_points, $other_points);
}

// DETERMINES IF IS AN EMAIL
function valid_email($address)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function message($mes, $dest = "", $time = "3", $topnav = FALSE, $menu = TRUE)
{
	$parse['mes']   = $mes;

	$page = parsetemplate(gettemplate('general/message_body'), $parse);

	if ( ! defined('IN_ADMIN'))
	{
		display($page, $topnav, (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : ""), FALSE, $menu);
	}
	else
	{
		display($page, $topnav, (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : ""), TRUE, FALSE);
	}

}

function display($page, $topnav = TRUE, $metatags = '', $AdminPage = FALSE, $menu = TRUE, $onload='')
{
	global $db, $debug, $user, $planetrow;

	if ( ! $AdminPage)
		$DisplayPage  = StdUserHeader($metatags, $onload);
	else
		$DisplayPage  = AdminUserHeader($metatags);

	if ($topnav  && ! $AdminPage)
	{
		require_once(XN_ROOT.'includes/functions/ShowTopNavigationBar.php');
		$DisplayPage .= ShowTopNavigationBar($user, $planetrow);
	}
	elseif ($topnav)
	{
		require_once(XN_ROOT.'includes/functions/adm/ShowTopNavigationBar.php');
		$DisplayPage .= ShowTopNavigationBar();
	}

	if ($menu && ! $AdminPage)
	{
		require_once(XN_ROOT.'includes/functions/ShowLeftMenu.php');
		$DisplayPage .= ShowLeftMenu($user);
	}
	elseif ($menu)
	{
		require_once(XN_ROOT.'includes/functions/adm/ShowMenu.php');
		$DisplayPage .= ShowMenu();
	}

	$DisplayPage .= $page;

	$debug->log_php();

	$footer		  = array();

	if ( ! defined('INSTALL') && AUTHLEVEL === 3 && read_config('debug'))
		$footer['debug'] = $debug->echo_log();

	if ( ! defined('LOGIN') && ! defined('IN_ADMIN') && isset($_GET['page']) && $_GET['page'] !== 'galaxy')
		$DisplayPage .= parsetemplate(gettemplate('general/footer'), $footer);
	elseif (defined('IN_ADMIN'))
		$DisplayPage .= parsetemplate(gettemplate('adm/footer'), $footer);

	echo $DisplayPage;

	if ($db) $db->close();
	die();
}

function StdUserHeader($metatags = '', $onload = '')
{
	$parse = array();
	$parse['-title-']	= read_config('game_name');
	$parse['-favi-']	= '<link rel="icon" href="'.GAMEURL.'favicon.png">';
	$parse['-meta-']	= '<meta charset="UTF-8">';
	$parse['-meta-']	.= '<meta name="generator" content="xNova '.VERSION.'">';
	$parse['-meta-']	.= '<meta name="author" content="Razican">';
	$parse['-meta-']	.= '<meta name="application-name" content="xNova">';
	//TODO description, keywords, link author, link license

	if ( ! defined('LOGIN'))
	{
		$parse['-style-']	= '<link rel="stylesheet" type="text/css" href="'.DPATH.'css/ingame.css">';
		$parse['-style-']	.= '<link rel="stylesheet" type="text/css" href="'.GAMEURL.'styles/css/default.css">';
		$parse['-style-']	.= '<link rel="stylesheet" type="text/css" href="'.GAMEURL.'styles/css/formate.css">';
		$parse['-style-']	.= '<link rel="stylesheet" type="text/css" href="'.DPATH.'formate.css">';
		$parse['-meta-']	.= '<script charset="UTF-8" src="'.GAMEURL.'js/overlib.min.js"></script>';
	}
	else
	{
		$parse['-style-']	= '<link rel="stylesheet" type="text/css" href="'.GAMEURL.'styles/css/styles.css">';
	}

	$parse['-meta-']		.= ($metatags) ? $metatags : '';
	$parse['onload']		= ! empty($onload) ? ' onload="'.$onload.'"' : '';

	return parsetemplate(gettemplate('general/simple_header'), $parse);
}

function AdminUserHeader($metatags = '')
{
	global $lang;
	$parse	= $lang;

	if ( ! isset($lang['']))

	if ( ! defined('IN_ADMIN'))
		$parse['-title-'] 	= 	'xNova - Instalación';
	else
		$parse['-title-'] 	= 	read_config('game_name').' - Admin CP';

	$parse['-favi-']	 = '<link rel="icon" href="'.GAMEURL.'favicon.png">';
	$parse['-style-']	 = '<link rel="stylesheet" type="text/css" href="'.DPATH.'css/admin.css">';
	$parse['-meta-']	 = '<meta charset="UTF-8">';
	$parse['-meta-']	.= '<meta name="generator" content="xNova '.VERSION.'">';
	$parse['-meta-']	.= '<meta name="author" content="Razican">';
	$parse['-meta-']	.= '<meta name="application-name" content="xNova">';
	//TODO description, keywords, link author, link license

	$parse['-meta-'] 	.= ($metatags) ? $metatags : '';

	return parsetemplate(gettemplate('adm/simple_header'), $parse);
}

function CalculateMaxPlanetFields(&$planet)
{
	global $resource;
	return $planet["field_max"] + ($planet[$resource[33]] * FIELDS_BY_TERRAFORMER);
}

function GetGameSpeedFactor()
{
	return read_config('fleet_speed') / 2500;
}

function ShowBuildTime($time)
{
	global $lang;
	return "<br>".$lang['fgf_time'] . Format::pretty_time($time);
}

function parsetemplate($template, $array = array())
{
	$array['skin_url']	= DPATH;
	return preg_replace('#\{([a-z0-9\-_]*?)\}#Ssie', '(isset($array[\'\1\']) ? $array[\'\1\'] : \'\');', $template);
}

function gettemplate($templatename)
{
	return file_get_contents(XN_ROOT.TEMPLATE_DIR.'/'.$templatename.'.php');
}

function includeLang($filename)
{
	global $lang;

	include(XN_ROOT."language/". DEFAULT_LANG."/".$filename.'.php');
}

function GetStartAdressLink($FleetRow, $FleetType)
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$FleetRow['fleet_start_galaxy']."&system=".$FleetRow['fleet_start_system']."\" ".$FleetType." >";
	$Link .= "[".$FleetRow['fleet_start_galaxy'].":".$FleetRow['fleet_start_system'].":".$FleetRow['fleet_start_planet']."]</a>";
	return $Link;
}

function GetTargetAdressLink($FleetRow, $FleetType)
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$FleetRow['fleet_end_galaxy']."&system=".$FleetRow['fleet_end_system']."\" ".$FleetType." >";
	$Link .= "[".$FleetRow['fleet_end_galaxy'].":".$FleetRow['fleet_end_system'].":".$FleetRow['fleet_end_planet']."]</a>";
	return $Link;
}

function BuildPlanetAdressLink($CurrentPlanet)
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$CurrentPlanet['galaxy']."&system=".$CurrentPlanet['system']."\">";
	$Link .= "[".$CurrentPlanet['galaxy'].":".$CurrentPlanet['system'].":".$CurrentPlanet['planet']."]</a>";
	return $Link;
}

function doquery($query, $table = NULL, $fetch = FALSE)
{
	global $db, $debug, $numqueries;

	require(XN_ROOT.'config.php');
	if ( ! isset($dbsettings)) die();

	if (empty($db))
	{
		$db		= new mysqli($dbsettings["server"], $dbsettings["user"], $dbsettings["pass"], $dbsettings["name"]);
		if ( ! is_null($db->connect_error)) $debug->error($db->connect_error, "SQL Error");

		$db->set_charset('utf8');
	}

	$sql 		= is_null($table) ? $query : str_replace("{{table}}", $dbsettings["prefix"].$table, $query);
	$sqlquery 	= $db->query($sql);
	if ( ! $sqlquery) $debug->error($db->error."<section class=\"sql-query\">".$sql."</section>", "SQL Error");

	unset($dbsettings);
	$numqueries++;

	$debug->add("<div class=\"query\"><section class=\"query-counter\">Query ".$numqueries.":</section><section class=\"query-text\">".htmlentities($query, ENT_COMPAT, 'UTF-8')."</section><section class=\"query-table\">".$table."</section><section class=\"query-fetch\"><figure class=\"".($fetch ? 'true' : 'false')."\"></figure></section></div>");

	if ($fetch)
		return $sqlquery->fetch_array();
	else
		return $sqlquery;
}

function catch_error($errno, $errstr, $errfile, $errline)
{
	global $user, $db, $debug;

	if ( ! (error_reporting() & $errno))
	{
		return;
	}

	if ( ! $db)
	{
		require(XN_ROOT.'config.php');

		if (isset($dbsettings))
		{
			$db		= new mysqli($dbsettings["server"], $dbsettings["user"], $dbsettings["pass"], $dbsettings["name"]);
			if ( ! is_null($db->connect_error)) $debug->error($db->connect_error, "SQL Error");
		}
		else
		{
			return FALSE;
		}
	}

	if ($errno === 2047 OR $errno === 6143 OR $errno === 30719) $errno = 32767;

	if (read_config('errors_'.$errno))
	{
		$errfile	= str_replace(XN_ROOT, 'XN_ROOT/', $errfile);
		$sender		= isset($user['id']) ? intval($user['id']) : 0;
		$errstr		= str_replace('[<a href=\'', '[<a target="_blank" href=\'http://php.net/manual/%lang%/', $errstr);

		$debug->php_error($sender, $errno, $errstr, $errfile, $errline);
	}

	return TRUE;
}

function show_date($date = NULL)
{
	global $lang;

	if (is_null($date)) $date = time();
	$format = read_config('date_format');

	$weekday = date("w", $date);
	$day = date("d", $date);
	$day_wo_zero = date("j", $date);
	$month = date("m", $date);
	$month_wo_zero = date("n", $date);
	$year = date("Y", $date);
	$shortyear = substr($year, -2);

	$final_date = $format;

	$final_date = str_replace("WEEKDAY", $lang['days'][$weekday], $final_date);
	$final_date = str_replace("WEEKDSHORT", $lang['dayshort'][$weekday], $final_date);
	$final_date = str_replace("DAY0", $day_wo_zero, $final_date);
	$final_date = str_replace("DAY", $day, $final_date);
	$final_date = str_replace("MONTHNAME", $lang['months'][$month_wo_zero-1], $final_date);
	$final_date = str_replace("MONTHSHORT", $lang['monthshort'][$month_wo_zero-1], $final_date);
	$final_date = str_replace("MONTH0", $month_wo_zero, $final_date);
	$final_date = str_replace("MONTH", $month, $final_date);
	$final_date = str_replace("SHORTYEAR", $shortyear, $final_date);
	$final_date = str_replace("YEAR", $year, $final_date);
	$final_date = str_replace("OF", $lang['of'], $final_date);

	return $final_date;
}


/* End of file GeneralFunctions.php */
/* Location: ./includes/GeneralFunctions.php */