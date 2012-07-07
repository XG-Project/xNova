<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if ( !defined('INSIDE') ) die(header("location:../"));

	// VERSION
	define('SYSTEM_VERSION' 			, '2.10.3');

	// TEMPLATES DEFAULT SETTINGS
	define('SKIN_PATH'					, 'styles/skins/');
	define('TEMPLATE_DIR'     		 	, 'styles/views/');
	define('DEFAULT_SKINPATH' 		 	, 'styles/skins/xgproyect/');

	// ADMINISTRATOR EMAIL AND GAME URL - THIS DATA IS REQUESTED BY REG.PHP
	define('ADMINEMAIL'               	, "info@xgproyect.com");
	define('GAMEURL'                  	, "http://".$_SERVER['HTTP_HOST']."/");

	// UNIVERSE DATA, GALAXY, SYSTEMS AND PLANETS || DEFAULT 9-499-15 RESPECTIVELY
	define('MAX_GALAXY_IN_WORLD'      	,       9);
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
	define('BASE_STORAGE_SIZE'        	,  100000);
	define('BUILD_METAL'              	,     500);
	define('BUILD_CRISTAL'            	,     500);
	define('BUILD_DEUTERIUM'          	, 	    0);

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
	define('DEBRIS_LIFE_TIME'      		,  604800);
	define('DEBRIS_MIN_VISIBLE_SIZE'	, 	  300);

?>