<?php

/**
 * @project XG Proyect
 * @version 2.10.x build 0000
 * @copyright Copyright (C) 2008 - 2012
 */

if(!defined('INSIDE')){ die(header("location:../../"));}

	function HandleTechnologieBuild ( &$CurrentPlanet, &$CurrentUser )
	{
		global $resource;

		if ($CurrentUser['b_tech_planet'] != 0)
		{
			if ($CurrentUser['b_tech_planet'] != $CurrentPlanet['id'])
				$WorkingPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". intval($CurrentUser['b_tech_planet']) ."';", 'planets', TRUE);

			if ($WorkingPlanet)
				$ThePlanet = $WorkingPlanet;
			else
				$ThePlanet = $CurrentPlanet;

			if ($ThePlanet['b_tech']    <= time() && $ThePlanet['b_tech_id'] != 0)
			{
				$CurrentUser[$resource[$ThePlanet['b_tech_id']]]++;

				$QryUpdatePlanet  = "UPDATE {{table}} SET ";
				$QryUpdatePlanet .= "`b_tech` = '0', ";
				$QryUpdatePlanet .= "`b_tech_id` = '0' ";
				$QryUpdatePlanet .= "WHERE ";
				$QryUpdatePlanet .= "`id` = '". intval($ThePlanet['id']) ."';";
				doquery( $QryUpdatePlanet, 'planets');

				$QryUpdateUser    = "UPDATE {{table}} SET ";
				$QryUpdateUser   .= "`".$resource[$ThePlanet['b_tech_id']]."` = '". $CurrentUser[$resource[$ThePlanet['b_tech_id']]] ."', ";
				$QryUpdateUser   .= "`b_tech_planet` = '0' ";
				$QryUpdateUser   .= "WHERE ";
				$QryUpdateUser   .= "`id` = '". intval($CurrentUser['id']) ."';";
				doquery( $QryUpdateUser, 'users');

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
				doquery("UPDATE {{table}} SET `b_tech_planet` = '0'  WHERE `id` = '". intval($CurrentUser['id']) ."';", 'users');
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