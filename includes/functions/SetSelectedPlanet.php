<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("location:../../"));

	function SetSelectedPlanet ( &$CurrentUser)
	{

		$SelectPlanet  = isset($_GET['cp']) ? intval($_GET['cp']) : NULL;
		$RestorePlanet = isset($_GET['re']) ? intval($_GET['re']) : NULL;

		// ADDED && $SelectPlanet != 0 THIS PREVENTS RUN A QUERY WHEN IT'S NOT NEEDED.
		if (isset($SelectPlanet) && is_numeric($SelectPlanet) && isset($RestorePlanet) && $RestorePlanet == 0 && $SelectPlanet != 0)
		{
			$IsPlanetMine   = doquery("SELECT `id` FROM {{table}} WHERE `id` = '". $SelectPlanet ."' AND `id_owner` = '". intval($CurrentUser['id']) ."';", 'planets', TRUE);

			if ($IsPlanetMine)
			{
				$CurrentUser['current_planet'] = $SelectPlanet;
				doquery("UPDATE {{table}} SET `current_planet` = '". $SelectPlanet ."' WHERE `id` = '".intval($CurrentUser['id'])."';", 'users');
			}
		}
	}

?>