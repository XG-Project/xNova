<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../"));

// VERSION
define('SYSTEM_VERSION' 			, '1.0.0-dev');
define('SCRIPT' 					, 'xNova');

// TEMPLATES DEFAULT SETTINGS
define('SKIN_PATH'					, 'styles/skins/');
define('TEMPLATE_DIR'     		 	, 'styles/views/');
define('DEFAULT_SKINPATH' 		 	, 'styles/skins/xnova/');

// ADMINISTRATOR EMAIL && GAME URL - THIS DATA IS REQUESTED BY REG.PHP
define('ADMINEMAIL'					, "admin@razican.com");
if (isset($_SERVER['HTTP_HOST']))
{
	$base_url	= ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? 'https' : 'http';
	$base_url	.= '://'.$_SERVER['HTTP_HOST'];
	$base_url	.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
}
else
{
	$base_url = 'http://localhost/';
}
define('GAMEURL'					, $base_url);

// UNIVERSE DATA, GALAXY, SYSTEMS && PLANETS OR DEFAULT 9-499-15 RESPECTIVELY
define('MAX_GALAXY_IN_WORLD'		,       9);
define('MAX_SYSTEM_IN_GALAXY'     	,     499);
define('MAX_PLANET_IN_SYSTEM'     	,      15);

// NUMBER OF COLUMNS FOR SPY REPORTS
define('SPY_REPORT_ROW'           	,       3);

// FIELDS FOR EACH LEVEL OF THE LUNAR BASE
define('FIELDS_BY_MOONBASIS_LEVEL'	,       3);

// FIELDS FOR EACH LEVEL OF THE TERRAFORMER
define('FIELDS_BY_TERRAFORMER'	  	,       5);

// NUMBER OF PLANETS THAT MAY HAVE A PLAYER
define('MAX_PLAYER_PLANETS'       	,       9);

// NUMBER OF BUILDINGS THAT CAN GO IN THE CONSTRUCTION QUEUE
define('MAX_BUILDING_QUEUE_SIZE'  	,       5);

// NUMBER OF SHIPS THAT CAN BUILD FOR ONCE
define('MAX_FLEET_OR_DEFS_PER_ROW'	, 1000000);

//PLANET SIZE MULTIPLER
define('PLANETSIZE_MULTIPLER'		,       1);

// INITIAL RESOURCE OF NEW PLANETS
define('BASE_STORAGE_SIZE'			,  100000);
define('BUILD_METAL'				,     500);
define('BUILD_CRISTAL'				,     500);
define('BUILD_DEUTERIUM'			, 	    0);

// OFFICIERS DEFAULT VALUES
define('AMIRAL'				  		,       2);
define('ENGINEER_DEFENSE'			,       2);
define('ENGINEER_ENERGY'			,     0.5);
define('GEOLOGUE'				  	,     0.1);
define('TECHNOCRATE_SPY'			,       2);
define('TECHNOCRATE_SPEED'			,    0.25);

// TRADER DARK MATTER DEFAULT VALUE
define('TR_DARK_MATTER'			  	,    3500);

// INVISIBLES DEBRIS
define('DEBRIS_LIFE_TIME'      		,  604800); //7*24*60*60
define('DEBRIS_MIN_VISIBLE_SIZE'	, 	  300);


/* End of file constants.php */
/* Location: ./includes/constants.php */