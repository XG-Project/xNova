<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if (!defined('INSIDE')){die();}

function phpself()
{
	$file = pathinfo($_SERVER['PHP_SELF']);

	if (version_compare(PHP_VERSION, '5.2.0', '<'))
	{
		$file['filename'] = substr($file['basename'], 0, strlen($file['basename']) - strlen($file['extension']) - 1);
	}

	if (basename(XGP_ROOT) != '.')
	{
		return basename($file['dirname']).'/'.$file['filename'];
	}
	else
	{
		return $file['filename'];
	}
}

// return if a name of php file is same of $name
function is_phpself($name)
{
	return (phpself() == $name);
}

/**
 * This system is little similar to the punBB hook system, but it is more
 * insecured and lazy.
 *
 * Now the code must be populated with:
 * ($hook = get_hook('NameOfHook')) ? eval($hook) : null;
 */

// this function store code into an array
function set_hook($name, $code)
{
	global $plugins_hooks;

	$plugins_hooks[$name][] = $code;
}
// this is used to get all code for the hook
function get_hook($name)
{
	global $plugins_hooks;

	if (isset($plugins_hooks[$name]))
	{
		return implode('', $plugins_hooks[$name]);
	}
}
/**
 * This system say if the plugin is on or not.
 *
 * Now the code must be:
 * PluginAct($name_of_the_plugin);
 * @author adri93
 */
function PluginAct($name)
{
    $Exists = doquery("SELECT status FROM {{table}} WHERE `plugin` = '" . $name . "' LIMIT 1;", "plugins", TRUE);
	if(!$Exists) doquery("INSERT INTO {{table}} SET `plugin` = '" . $name . "';", "plugins");

	return ($Exists[0]);
}
/**
 * Make a table for the configuration page of the plugin system
 *
 * Now the code must be:
 * AdmPlugin($name_of_the_plugin, $description);
 * @author adri93
 */
function AdmPlugin($name, $desc)
{
	$page   =   $_GET['modo'];
	if(is_phpself('adm/SettingsPage') && $page=='plugins')
	{
	    $activado		= PluginAct($name);
	    $config_line	.= "<tr>";

	    if($activado == "1")
	    { //if the plugin is on
	    	$config_line .= "<td class=\"c\" style=\"color:#FFFFFF\">".$name."</td>";
	    	$config_line .= "<td align=\"left\" class=\"c\" style=\"color:green\"><b>On</b></td>";
	    	$config_line .= "<td align=\"center\" class=\"c\" width=\"20px\" style=\"color:#FFFFFF\"><a href=\"SettingsPage.php?modo=plugins&desactivate=".$name."\">Desactivar</a></td>";
	    }
		else
		{ //if the plugin is off
	    	$config_line .= "<td class=\"c\" style=\"color:#FFFFFF\"><a href=\"#\" onMouseOver='return overlib(\"".$desc."\", CENTER, OFFSETX, 120, OFFSETY, -40, WIDTH, 250);' onMouseOut='return nd();' class=\"big\">".$name."</a></td>";
	    	$config_line .= "<td align=\"left\" class=\"c\" style=\"color:red\"><b>Off</b></td>";
	    	$config_line .= "<td align=\"center\" class=\"c\" width=\"20px\" style=\"color:#FFFFFF\"><a href=\"SettingsPage.php?modo=plugins&activate=".$name."\">Activar</a></td>";
	    }
	    $config_line .= "</tr>";
	}
	return ($config_line);
}

$config_line  		= "";
$plugins_path 		= XGP_ROOT . 'includes/plugins/';
$plugins_version 	= '0.3';
$plugins_hooks 		= array();

// open all files inside plugins folder

$dir = opendir($plugins_path);

while (($file = readdir($dir)) !== FALSE)
{
	// we check if the file is a include file
	$extension = '.'.substr($file, -3);
	// and include once the file
	if ($extension == '.php')
	{
		include $plugins_path . $file;
	}
	elseif (file_exists($plugins_path.$file.'/'.$file.'.php'))
	{ // by the way, we check if the plugin is inside of a folder
		include $plugins_path.$file.'/'.$file.'.php';
	}
}

closedir($dir);
/**
*Plugins admin panel
*This is a little plugin integrated in the system
*for control the others plugins.
*@author adri93
*/
if ( defined('IN_ADMIN') )
{
	$lang['mu_settings'] 	.= '</a></th></tr><tr><th ".$onMouseOverIE." class="ForIE"><a href="SettingsPage.php?modo=plugins" target="Hauptframe">Config. plugins';
	define('DPATH' , "../". DEFAULT_SKINPATH );
	//DPATH     				= "../". DEFAULT_SKINPATH  ;
	$page   				=   $_GET['modo'];

	if(is_phpself('adm/SettingsPage') && $page=='plugins')
	{
		//Si existe activar, activamos el plugin, xD
		if($_GET['activate'])
		{
			$plugin = $_GET['activate'];
			$ex 	= doquery("SELECT status FROM {{table}} WHERE `plugin`='". $plugin ."' LIMIT 1", 'plugins', TRUE);

			if ( $ex )
			{
				doquery("UPDATE {{table}} SET `status` = 1 WHERE `plugin`='".$plugin."' LIMIT 1", "plugins");
				$info = "<big>Plugin Activado</big>";
			}
		}
		//Si existe desactivar, lo desactivamos
		if($_GET['desactivate'])
		{
			$plugin = $_GET['desactivate'];
			$ex 	= doquery("SELECT status FROM {{table}} WHERE `plugin`='". $plugin ."' LIMIT 1", 'plugins', TRUE);
			if ( $ex )
			{
				doquery("UPDATE {{table}} SET `status` = 0 WHERE `plugin`='". $plugin ."' LIMIT 1", "plugins");
				$info = "<h1>Plugin Desactivado</h1>";
			}
		}

		$settingsplug	='       <br><br>';
		$settingsplug 	.=' <h2>Plugins Panel</h2>';
		$settingsplug 	.= $info;
		$settingsplug 	.=' <br><table width="250">';
		$settingsplug 	.=' <tr>';
		$settingsplug 	.='     <td class="a" colspan="3" style="color:#FFFFFF"><strong> Plugins instalados </strong></td>';
		$settingsplug 	.=' </tr>';
		$settingsplug 	.= $config_line;
		$settingsplug 	.=' </table>';

		display ( $settingsplug , FALSE , '' , TRUE , FALSE );
	}
}
?>