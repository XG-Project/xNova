<?php

/**
 * @package	xNova
 * @version	1.0.x
 * @license	http://creativecommons.org/licenses/by-sa/3.0/ CC-BY-SA
 * @link	http://www.razican.com Author's Website
 * @author	Razican <admin@razican.com>
 */

if ( ! defined('INSIDE')) die(header("Location:../../"));

	function HandleTechnologieBuild (&$CurrentPlanet, &$CurrentUser)
	{
		global $resource;

		if ($CurrentUser['b_tech_planet'])
		{
			if ($CurrentUser['b_tech_planet'] != $CurrentPlanet['id'])
				$WorkingPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '".intval($CurrentUser['b_tech_planet'])."';", 'planets', TRUE);

			if ($WorkingPlanet)
				$ThePlanet = $WorkingPlanet;
			else
				$ThePlanet = $CurrentPlanet;

			if ($ThePlanet['b_tech']    <= time() && $ThePlanet['b_tech_id'])
			{
				$CurrentUser[$resource[$ThePlanet['b_tech_id']]]++;

				$QryUpdatePlanet  = "UPDATE {{table}} SET ";
				$QryUpdatePlanet .= "`b_tech` = '0', ";
				$QryUpdatePlanet .= "`b_tech_id` = '0' ";
				$QryUpdatePlanet .= "WHERE ";
				$QryUpdatePlanet .= "`id` = '".intval($ThePlanet['id'])."';";
				doquery($QryUpdatePlanet, 'planets');

				$QryUpdateUser    = "UPDATE {{table}} SET ";
				$QryUpdateUser   .= "`".$resource[$ThePlanet['b_tech_id']]."` = '".$CurrentUser[$resource[$ThePlanet['b_tech_id']]]."', ";
				$QryUpdateUser   .= "`b_tech_planet` = '0' ";
				$QryUpdateUser   .= "WHERE ";
				$QryUpdateUser   .= "`id` = '".intval($CurrentUser['id'])."';";
				doquery($QryUpdateUser, 'users');

				$ThePlanet["b_tech_id"] = 0;

				if (isset($WorkingPlanet))
					$WorkingPlanet = $ThePlanet;
				else
					$CurrentPlanet = $ThePlanet;

				$Result['WorkOn'] = "";
				$Result['OnWork'] = FALSE;
			}
			elseif ($ThePlanet["b_tech_id"] == 0)
			{
				doquery("UPDATE {{table}} SET `b_tech_planet` = '0'  WHERE `id` = '".intval($CurrentUser['id'])."';", 'users');
				$Result['WorkOn'] = "";
				$Result['OnWork'] = FALSE;
			}
			else
			{
				$Result['WorkOn'] = $ThePlanet;
				$Result['OnWork'] = TRUE;
			}
		}
		else
		{
			$Result['WorkOn'] = "";
			$Result['OnWork'] = FALSE;
		}

		return $Result;
	}
?>