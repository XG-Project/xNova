<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

define('INSIDE'  , TRUE);
define('INSTALL' , FALSE);
define('IN_ADMIN', TRUE);
define('XGP_ROOT', './../');

include(XGP_ROOT . 'global.php');
include(XGP_ROOT . 'adm/statfunctions.php');

if ($user['authlevel'] < 2) die(message ($lang['not_enough_permissions']));

	$result			= MakeStats();
	$memory_p		= str_replace(array("%p", "%m"), $result['memory_peak'], $lang['sb_top_memory']);
	$memory_e		= str_replace(array("%e", "%m"), $result['end_memory'], $lang['sb_final_memory']);
	$memory_i		= str_replace(array("%i", "%m"), $result['initial_memory'], $lang['sb_start_memory']);
	$stats_end_time	= str_replace("%t", $result['totaltime'], $lang['sb_stats_update']);
	$stats_block	= str_replace("%n", $result['amount_per_block'], $lang['sb_users_per_block']);

	update_config( 'stat_last_update', $result['stats_time']);

	$using_flying 	= ( ( read_config ( 'stat_flying' ) == 1 ) ? $lang['sb_using_fleet_array'] : $lang['sb_using_fleet_query']);

	message($lang['sb_stats_updated'].$stats_end_time.$memory_i.$memory_e.$memory_p.$stats_block.$using_flying);


?>