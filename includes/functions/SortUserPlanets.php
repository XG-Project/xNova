<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @since	1.0.0
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

function SortUserPlanets ($CurrentUser)
{
	$Order = ($CurrentUser['planet_sort_order'] == 1) ? "DESC" : "ASC" ;
	$Sort  = $CurrentUser['planet_sort'];

	$QryPlanets  = "SELECT `id`, `name`, `galaxy`, `system`, `planet`, `planet_type` FROM `{{table}}` WHERE `id_owner` = '".intval($CurrentUser['id'])."' && `destruyed` = 0 ORDER BY ";

	if ($Sort == 0)
		$QryPlanets .= "`id` ".$Order;
	elseif ($Sort == 1)
		$QryPlanets .= "`galaxy`, `system`, `planet`, `planet_type` ".$Order;
	elseif ($Sort == 2)
		$QryPlanets .= "`name` ".$Order;

	$Planets = doquery($QryPlanets, 'planets');

	return $Planets;
}
?>