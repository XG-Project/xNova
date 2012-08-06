<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

function unset_vars ( $prefix )
{
	$vars = array_keys ( $GLOBALS );

	for( $n = 0, $i = 0;  $i < count($vars);  $i ++ )
	{
		if ( strpos ( $vars[$i] , $prefix ) === 0 )
		{
			unset ( $GLOBALS[$vars[$i]] );

			$n ++;
		}
	}

	return  $n;
}

// READS CONFIGURATIONS
function read_config ( $config_name = '' , $all = FALSE )
{
	$configs		= xml::getInstance ( 'config.xml' );

	if ( $all )
	{
		return $configs->get_configs ();
	}
	else
	{
		return $configs->get_config ( $config_name );
	}

}

// WRITES CONFIGURATIONS
function update_config ( $config_name, $config_value )
{
	$configs		= xml::getInstance ( 'config.xml' );

	$configs->write_config ( $config_name , $config_value );
}

// DETERMINES IF THE PLAYER IS WEAK
function is_weak ( $current_points , $other_points )
{
	$weak	= NoobsProtection::getInstance();

	if ( $weak->is_weak ( $current_points , $other_points ) )
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// DETERMINES IF THE PLAYER IS STRONG
function is_strong ( $current_points , $other_points )
{
	$strong	= NoobsProtection::getInstance();

	if ( $strong->is_weak ( $current_points , $other_points ) )
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// DETERMINES IF IS AN EMAIL
function valid_email ( $address )
{
	return ( !preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix" , $address ) ) ? FALSE : TRUE;
}

function message ( $mes , $dest = "" , $time = "3" , $topnav = FALSE , $menu = TRUE )
{
	$parse['mes']   = $mes;

	$page .= parsetemplate ( gettemplate ( 'general/message_body' ) , $parse );

	if ( !defined ( 'IN_ADMIN' ) )
	{
		display ( $page , $topnav , ( ( $dest != "" ) ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : "") , FALSE , $menu );
	}
	else
	{
		display ( $page , $topnav , ( ( $dest != "" ) ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : "") , TRUE , FALSE );
	}

}

function display ($page, $topnav = TRUE, $metatags = '', $AdminPage = FALSE, $menu = TRUE)
{
	global $link, $debug, $user, $planetrow;

	if (!$AdminPage)
		$DisplayPage  = StdUserHeader($metatags);
	else
		$DisplayPage  = AdminUserHeader($metatags);

	if ($topnav)
	{
		include_once(XGP_ROOT . 'includes/functions/ShowTopNavigationBar.php');
		$DisplayPage .= ShowTopNavigationBar( $user, $planetrow );
	}

	if ($menu && !$AdminPage)
	{
		include_once(XGP_ROOT . 'includes/functions/ShowLeftMenu.php');
		$DisplayPage .= ShowLeftMenu ($user);
	}

	$DisplayPage .= "\n<center>\n". $page ."\n</center>\n";

	if(!defined('LOGIN') && $_GET['page'] != 'galaxy')
		$DisplayPage .= parsetemplate ( gettemplate ( 'general/footer' ) , $parse );

	if ( $user['authlevel'] == 3 && read_config ( 'debug' ) == 1 && !$AdminPage )
	{
		// Convertir a objeto dom
		$DisplayPage = str_get_html($DisplayPage);

		// Modificar div#content
		$content = $DisplayPage->find("div#content", 0);

		// Contenido debug
		$content->innertext .= $debug->echo_log();
	}

	echo $DisplayPage;

	if ( $user['authlevel'] == 3 && read_config ( 'debug' ) == 1 && $AdminPage && !defined('NO_DEBUG') )
	{

		echo "<center>";
		echo $debug->echo_log();
		echo "</center>";
	}

	if ( $link )
	{
		mysql_close ( $link );
	}

	die();
}

function StdUserHeader ($metatags = '')
{
	$parse['-title-'] 	.= read_config ( 'game_name' );
	$parse['-favi-']	.= "<link rel=\"shortcut icon\" href=\"./favicon.ico\">\n";
	$parse['-meta-']	.= "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">\n";
	$parse['-meta-']	.= "<meta name=\"generator\" content=\"XG Proyect " . VERSION . "\" />\n";

	if(!defined('LOGIN'))
	{
		$parse['-style-']  	.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/css/default.css\">\n";
		$parse['-style-']  	.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/css/formate.css\">\n";
		$parse['-style-'] 	.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". DPATH ."formate.css\" />\n";
		$parse['-meta-']	.= "<script type=\"text/javascript\" src=\"js/overlib-min.js\"></script>\n";
	}
	else
	{
		$parse['-style-']  	.= "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/css/styles.css\">\n";
	}

	$parse['-meta-']	.= ($metatags) ? $metatags : "";

	return parsetemplate ( gettemplate ( 'general/simple_header' ) , $parse );
}

function AdminUserHeader ($metatags = '')
{
	if (!defined('IN_ADMIN'))
	{
		$parse['-title-'] 	.= 	"XG Proyect - Install";
	}
	else
	{
		$parse['-title-'] 	.= 	read_config ( 'game_name' ) . " - Admin CP";
	}


	$parse['-favi-']	.= 	"<link rel=\"shortcut icon\" href=\"./../favicon.ico\">\n";
	$parse['-style-']	.=	"<link rel=\"stylesheet\" type=\"text/css\" href=\"./../styles/css/admin.css\">\n";
	$parse['-meta-']	.= 	"<script type=\"text/javascript\" src=\"./../js/overlib-min.js\"></script>\n";
	$parse['-meta-'] 	.= ($metatags) ? $metatags : "";

	return parsetemplate ( gettemplate ( 'adm/simple_header' ) , $parse );
}

function CalculateMaxPlanetFields (&$planet)
{
	global $resource;
	return $planet["field_max"] + ($planet[ $resource[33] ] * FIELDS_BY_TERRAFORMER);
}

function GetGameSpeedFactor ()
{
	return read_config ( 'fleet_speed' ) / 2500;
}

function ShowBuildTime($time)
{
	global $lang;
	return "<br>". $lang['fgf_time'] . Format::pretty_time($time);
}

function parsetemplate ( $template , $array )
{
	return preg_replace ( '#\{([a-z0-9\-_]*?)\}#Ssie' , '( ( isset($array[\'\1\']) ) ? $array[\'\1\'] : \'\' );' , $template );
}

function gettemplate ( $templatename )
{
	return @file_get_contents ( XGP_ROOT . TEMPLATE_DIR . '/' . $templatename . '.php' );
}

function includeLang ( $filename )
{
	global $lang;

	include ( XGP_ROOT . "language/" . DEFAULT_LANG ."/". $filename . '.php' );
}

function GetStartAdressLink ( $FleetRow, $FleetType )
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$FleetRow['fleet_start_galaxy']."&system=".$FleetRow['fleet_start_system']."\" ". $FleetType ." >";
	$Link .= "[".$FleetRow['fleet_start_galaxy'].":".$FleetRow['fleet_start_system'].":".$FleetRow['fleet_start_planet']."]</a>";
	return $Link;
}

function GetTargetAdressLink ( $FleetRow, $FleetType )
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$FleetRow['fleet_end_galaxy']."&system=".$FleetRow['fleet_end_system']."\" ". $FleetType ." >";
	$Link .= "[".$FleetRow['fleet_end_galaxy'].":".$FleetRow['fleet_end_system'].":".$FleetRow['fleet_end_planet']."]</a>";
	return $Link;
}

function BuildPlanetAdressLink ( $CurrentPlanet )
{
	$Link  = "<a href=\"game.php?page=galaxy&mode=3&galaxy=".$CurrentPlanet['galaxy']."&system=".$CurrentPlanet['system']."\">";
	$Link .= "[".$CurrentPlanet['galaxy'].":".$CurrentPlanet['system'].":".$CurrentPlanet['planet']."]</a>";
	return $Link;
}

function doquery ( $query , $table , $fetch = FALSE )
{
	global $link, $debug;

	require ( XGP_ROOT . 'config.php' );

	if ( !$link )
	{
		$link = mysql_connect	(
									$dbsettings["server"],
									$dbsettings["user"],
									$dbsettings["pass"]
								) or $debug->error ( mysql_error() . "<br />$query" , "SQL Error" );

		mysql_select_db ( $dbsettings["name"] ) or $debug->error ( mysql_error() . "<br />$query" , "SQL Error" );

		echo mysql_error();
	}

	$sql 		= str_replace ( "{{table}}" , $dbsettings["prefix"] . $table , $query );
	$sqlquery 	= mysql_query ( $sql ) or $debug->error ( mysql_error() . "<br />$sql<br />" , "SQL Error" );

	unset ( $dbsettings );

	global $numqueries,$debug;
	$numqueries++;

	$debug->add ( "<tr><th>Query $numqueries: </th><th>$query</th><th>$table</th><th>$fetch</th></tr>");

	if ( $fetch )
	{
		return mysql_fetch_array ( $sqlquery );
	}
	else
	{
		return $sqlquery;
	}

}

?>